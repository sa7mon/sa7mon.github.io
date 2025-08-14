---
type: "post"
title: "{{ replace (replaceRE `^\d{4}-\d{2}-\d{2}_` "" .Name 1) "-" " " | title }}"
date: {{ .Date }}
site: 
params:
    pub_date: {{ index (findRE `\d{4}-\d{2}-\d{2}$` .Name 1) 0 }}
link_tags: []
formats: []
---