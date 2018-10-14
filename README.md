# View Count Plugin for Craft CMS 3.x

Keep track of view counts over a period of time to show popular elements. Right now, it lumps views into aggregates by day. 

## Usage
After installing and enabling, all you need to do is register a view count in the template:
```twig
{% do craft.viewcount.increment(entry.id) %}
```

Of course, it doesn't need to be an entry id, it could be any element ID, such as a category.

To start using views in your query, all you need to do is use the "views" keyword in your `order` parameter. For example:

```twig
{% set blogPosts = craft.entries({
    section: 'blog',
    order: 'views DESC',
    limit: 10
}) %}
```

This will sort based on the number of views in the past 24 hours.

## Settings
Right now, there is a single setting to toggle whether view counts should be recorded to logged in users.

## Developer Events
The following events are emitted to allow for programmtic customization: 
#### EVENT_REGISTER_VIEW
You can listen for this event on the `venveo\viewcount\services\ViewCount` class to control whether a view count should be registered.
The event class, `ViewCountEvent`, contains:
- `$elementId` - The element ID being registered as a view
- `$siteId` - The id of the site the element resides on
- `$shouldSkip` - a boolean for whether the view should be skipped

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require venveo/view-count

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Popular Entries.

Brought to you by [Venveo](https://venveo.com)
