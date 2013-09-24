# rsscomics

This PHP script rewrites RSS feeds for GoComics to include the comic in the feed.

Supply the id of the comic in the query string
(e.g. http://localhost/rsscomics/?id=peanuts), and it will return the original
RSS feed for the comic, but with the content replaced with the actual comic.

This script requires SimpleXMLElement to work properly.

