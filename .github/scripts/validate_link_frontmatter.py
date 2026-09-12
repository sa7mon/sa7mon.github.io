#!/usr/bin/env python3
"""Validate the YAML frontmatter of content/links/*.md posts.

Usage:
    validate_link_frontmatter.py FILE [FILE ...]

Exits non-zero if any file fails schema validation or is missing a link URL
in its body. Prints GitHub Actions ::error:: annotations.
"""

import datetime
import pathlib
import sys
import json

import jsonschema
import yaml

SCHEMA_PATH = pathlib.Path(__file__).resolve().parent.parent / "schemas" / "link-post.schema.json"


def load_schema() -> dict:
    return json.loads(SCHEMA_PATH.read_text())


def json_safe(value):
    """Recursively convert YAML-native date/datetime objects to ISO strings.

    PyYAML auto-parses unquoted dates (e.g. `pub_date: 2025-08-19`) and
    datetimes (e.g. `date: 2025-08-16T02:47:42Z`) into Python date/datetime
    objects. JSON Schema validates against JSON-native types, so a
    correctly-typed date would otherwise be flagged as the wrong type.
    """
    if isinstance(value, datetime.datetime):
        return value.strftime("%Y-%m-%dT%H:%M:%SZ") if value.tzinfo is None else value.isoformat()
    if isinstance(value, datetime.date):
        return value.isoformat()
    if isinstance(value, dict):
        return {k: json_safe(v) for k, v in value.items()}
    if isinstance(value, list):
        return [json_safe(v) for v in value]
    return value


def split_frontmatter(text: str) -> tuple[str, str]:
    """Split a Hugo content file into (frontmatter, body). Raises ValueError if malformed."""
    if not text.startswith("---"):
        raise ValueError("file does not start with a '---' frontmatter delimiter")
    parts = text.split("---", 2)
    if len(parts) < 3:
        raise ValueError("could not find closing '---' frontmatter delimiter")
    return parts[1], parts[2]


def validate_file(path: pathlib.Path, schema: dict) -> list[str]:
    errors: list[str] = []
    text = path.read_text()

    try:
        frontmatter_text, body = split_frontmatter(text)
    except ValueError as e:
        return [f"malformed frontmatter block: {e}"]

    try:
        data = yaml.safe_load(frontmatter_text)
    except yaml.YAMLError as e:
        return [f"invalid YAML in frontmatter: {e}"]

    if not isinstance(data, dict):
        return ["frontmatter did not parse to a YAML mapping"]

    validator = jsonschema.Draft7Validator(schema)
    for err in sorted(validator.iter_errors(json_safe(data)), key=lambda e: list(e.absolute_path)):
        location = ".".join(str(p) for p in err.absolute_path) or "<root>"
        errors.append(f"{location}: {err.message}")

    if not any(line.strip().startswith(("http://", "https://")) for line in body.splitlines()):
        errors.append("body: expected a non-blank line starting with http:// or https:// (the link URL)")

    return errors


def main(argv: list[str]) -> int:
    if not argv:
        print("usage: validate_link_frontmatter.py FILE [FILE ...]", file=sys.stderr)
        return 2

    schema = load_schema()
    had_errors = False

    for arg in argv:
        path = pathlib.Path(arg)
        if not path.is_file():
            print(f"::error file={arg}::file not found")
            had_errors = True
            continue

        errors = validate_file(path, schema)
        for error in errors:
            had_errors = True
            print(f"::error file={arg}::{error}")

        if not errors:
            print(f"OK: {arg}")

    return 1 if had_errors else 0


if __name__ == "__main__":
    raise SystemExit(main(sys.argv[1:]))
