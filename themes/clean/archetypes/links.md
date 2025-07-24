---
type: "post"
title: "{{ replaceRE `^\d{4}-\d{2}-\d{2}_` "" .Name | replace .Name "-" " " | title }}"
date: {{ .Date }}
site: example.com
params:
    pub_date: {{ findRE `^\d{4}-\d{2}-\d{2}` .Name 1 }}
link_tags: []
formats: []
---