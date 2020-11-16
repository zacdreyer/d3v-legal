# d3v-legal
WordPress Plugin providing legal docs, like a privacy policy; copyright notice etc, currently only for South African websites. _This plugin comes with no warranty or support. We suggest you consult an attorney regarding compliance with the POPIA._

# Shortcode Usage:
``` [d3v-legal notice='' company='' email='' address='' tel='' smp=''] ```
* __notice__    - Type of notice you want to display, see usage examples below.
* __company__   - Full company name that is to be displayed in notices.
* __email__     - Legal department contact email address, if not available use generic, for Company indicated in "company" field.
* __address__   - Domicilie for Company indicated in "company" field.
* __tel__       - Public contact number for Company indicated in "company" field.
* __smp__       - When using notices that are linked to social media this field is used to specify the social media platforms.


# Shortcode Usage Examples:

### Cookie Notice
_Notice Value:_ cookies

``` [d3v-legal notice='cookies' company='ABC Holdings] ```


### Privacy Policy
_Notice Value:_ privacy
privacy_policy($atts['company'], $atts['address'], $atts['email'], $atts['tel'])

``` [d3v-legal notice='privacy' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' email='info@abc.com' tel='+27 82 000 0000'] ```


### Copyright Notice
_Notice Value:_ copyright

``` [d3v-legal notice='copyright' company='ABC Holdings'] ```


### Footer Copyright Notice
_Notice Value:_ copyrightfooter

``` [d3v-legal notice='copyrightfooter' company='ABC Holdings'] ```


### Disclaimer
_Notice Value:_ disclaimer

``` [d3v-legal notice='disclaimer' company='ABC Holdings'] ```


### Terms and Conditions
_Notice Value:_ tscs

``` [d3v-legal notice='tscs' company=ABC Holdings' address='21 Random Street, Somewhere, South Africa'] ```


### Competition Terms and Conditions
_Notice Value:_ comptscs

``` [d3v-legal notice='comptscs' company='ABC Holdings' email='info@abc.com' address='21 Random Street, Somewhere, South Africa' tel='+27 82 000 0000' smp='Facebook'] ```


### Contact
_Notice Value:_ contact

``` [d3v-legal notice='contact' company='ABC Holdings' email='info@abc.com'] ```


### Social Media Release Statement
_Notice Value:_ smr

``` [d3v-legal notice='smr' company='ABC Holdings' smp='Facebook'] ```


### Social Media Netiquette
_Notice Value:_ smn

``` [d3v-legal notice='smn' company='ABC Holdings' smp='Facebook'] ```
