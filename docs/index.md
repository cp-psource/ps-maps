---
layout: psource-theme
title: "PS Maps"
---

<h2 align="center" style="color:#38c2bb;">📚 PS Maps</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/ps-maps/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/ps-maps/releases" style="color:#38c2bb;">📝 Download</a>
</div>



DetailUsage
Start by reading Installing plugins section in our comprehensive
WordPress and WordPress Multisite Manual if you are new to WordPress.

To install:
1.  Download the plugin file
2.  Unzip the file into a folder on your hard drive
3.  Upload /wpmu_dev_maps_plugin/ folder to /wp-content/plugins/ folder on your site
4.  Login to your admin panel for WordPress or Multisite and activate the plugin:

On regular WordPress installs – visit Plugins and Activate the plugin.
For WordPress Multisite installs – Activate it blog-by-blog (say if you wanted to make it a Supporter premium plugin), or visit Network Admin -> Plugins and Network Activate the plugin.
To Use:
The Google Map plugin adds a “Add Map” icon to your visual editor. Once you’ve created your new map it is inserted into write Post / Page area as shortcode which looks like this: [map id="1"].

It also includes a Google Maps widget for displaying maps in your site’s sidebar as well as the ability to create mashups from your maps.

You’ll find detailed instructions for using this plugin by going to Settings > Google Maps plugin where you can also configure your preferred Google Maps defaults.

Configuring your default Google Maps options
1.  Go to Settings > Google Maps plugin

Google Maps Dash

2.  Select your preferred Google Maps defaults from the options available.

 

Google Maps Settings

The various default settings  control the Panoramia images for each of your new map locations. This can be pretty nice for locations that don’t have Street View option in Google Maps.

You control whether a map does or doesn’t display the Panoramia images widget below the map using the Map Options in the Add Map window. (See Adding a Google Map to a Post or Page further on below.)

The default map type controls which type of Google Map is embedded. Changes to Default map type applies to newly created maps only; not previously created maps.

Examples of Google map types

3. Custom Fields

Google Maps Custom Fields

The Custom Fields settings allow you to set specific Longitude and Latitudes, which when found in a post will automatically create a Google Map in it’s place. Which in combination with the default alignment and size settings mentioned earlier, can be quite the powerful tool for showing locations on your website!

4. Select your Add-ons.

Our Google Map plugin has all kinds of great Add-ons to give your maps some awesome features!

Google Maps Add-ons

Simply select which ones you’d like included, and you’re all set!

5. Don’t forget to click Save Changes!

Adding a Google Map to a Post or Page
The Google Map plugin adds a “Add Map” icon to your visual editor. Once you’ve created your new map it is inserted into write Post / Page area as shortcode which looks like this: [map id="1"].

It also adds a widget so you can add maps to your sidebar (see Appearance > Widgets).

Let’s take a look!

1.  When creating a new post or page, Click on the Add Map icon. Which will bring a pop over for creating new maps, or reusing already created ones.

Google Maps Add Map icon

 

Google Maps new map

2.  Add the address of the location you want to add and then click Add.  This adds a place marker to your map and displays the location at the bottom of the map.

3.  To add more location(s), just add the address(es) and then click Add.

4.  Alternatively you can add a location(s) by zooming in on your map, click on Drop Marker to add a Marker to your Map and then drag/drop it to the desired location.

5.  Be sure to give your map a name.

6.  Now zoom in on your place marker(s) so your readers have the best map view of your location.

Google Maps NYC

7.  Click on the Place Marker if you want to change the Title of location or Information about the location. You can also click on the Icon to change the Marker’s icon.

Google Maps Pin info

8.  Next click Save changes to this map.

9. Finally click on Map Options, select your preferences , click OK to close the Map Options window and then click Insert this map.  Select “Associate map with this post” if you want to be able to create mashups with your maps.

Map Options

10. You should now see your map shortcode displayed and your Google map embedded in your Post or Page when published.

Map ID

On load on the public facing pages, maps are always centered to the last existing marker location.

Adding your Google Map(s) to your sidebar
The Google Maps widget can be used to add an existing map, create a new map or add a mashup of maps to your site’s sidebar.

1.  Go to Appearance > Widgets

2.  Click and drag to Add the Google Maps Widget to the desired sidebar or widget area and configure the widget.

Google Maps Widget

Creating mashups of your maps
Mashups can be easily created by either using Map Query shortcode (for posts) or by setting them up in Widget Options (for widgets).

You need to have selected “Associate map with this post” in the Map Options window when creating a map for that map to be displayed using Map Query tag  shortcode.

 

The following shortcode can be used for the custom query in the Google Maps widget to create mashups:

[map query="tag=tagname"] – This shows all maps associated with any posts tagged with that tag on the site.  For example,  [map query="tag=New York"] displays all maps associated with posts tagged New York.

Note:

when you use this with the Widget, you only need to provide the actual query, in this case “tag=New York”, without the quotes as shown in the screenshot below:
 

Mashups 1

The following shortcodes can be used in posts to create mashups:

[map query="all"]  - This shows all maps associated with any posts on the site.

[map query="current"] – This shows all maps associated with posts currently on the site.

[map query="random"] – This shows a random existing map. It always displays a single map.

[map query="tag=tagname"] – This shows all maps associated with any posts tagged with that tag on the site.
For example,  [map query="tag=New York"] displays all maps associated with posts tagged New York.

Creating a map query for a post or page

The following attributes can be used with shortcodes:

overlay  - When set as true, this shows all markers from the queried posts overlayed on a single map.
width – You can set this to display a relative or absolute width for the map.
height – You can set this to display a relative or absolute height for the map.
show_images – Shows images when set to true and will hide them when false.
show_map – Setting this to false will not display the map, useful for when you want to show just the list of markers.
show_posts – Displays a link to the post for which a marker is associated.
map_type – Can be set to: “ROADMAP”, “SATELLITE”, “HYBRID”, or “TERRAIN” to have the map rendered in that mode of display.
network – When used on a Multisite, displays network post markers when set to true.
Shortcode examples:

As an example, you can use the following shortcode which displays a single map that’s as wide as possible (within theme constraints), which includes all markers from all posts on the current site.

[map query="all" overlay="true" show_posts="true" width="100%"]

The Following will show one map, which will have all markers from maps associated with all posts on the network that have the tag “my_tag”. It will have a list of markers displayed below it and the markers themselves will have a list of links to posts associated with their original map. The Panoramio image gallery will not be shown. The map itself will be as wide as possible (it will fill all available space), but it will be only 100px high.

[map query="tag=my_tag" network="true" overlay="true" show_images="false" show_posts="true" height="100px" width="100%"]

You can also use a variety of attributes to your shortcodes to create the exact map you want.  Zoom for instance can be setup using a number (1-20) or a label.  Here’s how the numbers relate to their respective labels:

’1′ => Earth
’3′ => Continent
’5′ => Region
’7′ => Nearby Cities
’12′ => City Plan
’15′ => Details

And here’s what a sample shortcode would look like:

[map id="2" zoom="18"]
