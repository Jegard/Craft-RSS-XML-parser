### Example use
```
{% set feed = craft.rssatomReader.feed('http://open.live.bbc.co.uk/weather/feeds/en/6296595/observations.rss') %}
{% if feed %}
h1 {{ feed.channel.item.title }}
{% endif %}
```
the feed function also accept cache duration and curl timeout options (both in seconds)
feed($url = null, $cache_duration = 86400, $timeout = 1000)
