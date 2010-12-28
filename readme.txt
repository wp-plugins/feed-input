=== Feed Input ===
Contributors: chertz
Tags: feed rss
Requires at least: 3.0.0
Tested up to: 3.0.2
Stable tag: 0.0.1A

Pull in feeds and then processes them for various uses include adding them to any post type. This plugin is in alpha stage.


== Description ==

*The plugin is currently in alpha stage of development. It does not have a UI for managing the plugin.*

Currently the plugin just provides the core functionality. You can have multiple feeds which can pull from multiple URIs. A feed has a processor which then handles new feed items. Currently the only processor is a processor that creates posts. A feed item is prevented from being processed more than once per feed. This means if you have two feeds that both pull from the same URI source, they will both get their once instance of the feed item, but any one feed will not process the feed item more than once.

== Installation ==


1. Upload `feed-input` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 0.0.1A =
* Initial version of the plugin