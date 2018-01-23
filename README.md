# Google Places Field for Gravity Forms

The Google Places Field for Gravity Forms provides a new field type that integrates with the [Google Places API][google places],
offering easy look-ups of addresses and certain points of interest. The user-selected entry from the results set
is submitted as the value for the Google Places form field, and additional information can be collected from
the Google Places API response data in separate form fields (hidden or exposed).

Integration with the Google Places API is made possible by the [Geocomplete jQuery plugin][geocomplete].

## Installation

You can download this plugin as a [ZIP file from GitHub][zip file] and install it on your WordPress site
[like any other plugin][plugin installation]. The [Gravity Forms plugin][gravity forms] is required as well,
and this plugin has been tested up to Gravity Forms v2.2.5. Once both plugins are installed and activated,
you can begin adding Google Places fields to your forms.

## How to Use

A new field type labeled "Google Places" should appear under the "Advanced Fields" section of the Gravity Forms
form editor. To use, simply drag a new Google Places field into the desired position. The field does not require
any additional configuration to work. If you just need to collect an address in its single-line form (as populated
in the field by the Google Places API), you're all set! If you need to collect additional place data, or collect
components of the selected address in their own fields, keep reading.

### Collecting Additional Data

The Google Places API returns more data about the selected address than what is populated in the Google Places field,
and these additional components can be collected in separate fields. As well, components that appear in the populated
value of the Google Places field can be collected in their own fields for easier data manipulation on form entries.
For instance, you may wish to collect the City, State, and/or Country values separately or collect the latitude
and longitude values of the address.

Address components can be populated into "Single Line Text" or "Hidden" fields. To collect additional address components,
add a new field of either of these types and select the "Advanced" tab of the edit form for the new field.
You should see a set of configuration options labeled "Populate with Google Places Address Component."
In the "Field ID" box, enter the field ID of the "Google Places Lookup" field that you wish to populate from.
The "Component" option should be filled with the address component you'd like to collect; for a list of possible
components, see the "Address and Places Component Types" section of the [Geocomplete documentation][geocomplete].
Some components include a shortened version (e.g. "RI" instead of "Rhode Island"), but by default,
the result will contain the full version. To access the short version, append `_short` to the component type.
For example, to collect the short value of the state value for a U.S. address, the component would be
`administrative_area_level_1_short`.

Multiple Google Places fields can be placed on a single form. When collecting additional address components,
ensure you are entering the correct field ID that corresponds to the places field.

## Issues

If you run into a problem with this plugin, please feel free to [open a new issue][new issue]!

[google places]: https://developers.google.com/places/
[geocomplete]: https://ubilabs.github.io/geocomplete/
[zip file]: https://github.com/oomphinc/gravity-forms-google-places/archive/master.zip
[plugin installation]: https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation
[gravity forms]: https://www.gravityforms.com/
[new issue]: https://github.com/oomphinc/gravity-forms-google-places/issues/new
