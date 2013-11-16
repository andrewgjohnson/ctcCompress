# ctcCompress

## Description

ctcCompress is a tool to handle front end assets including CSS files, Javascript files and images written in PHP.

## Usage

To use ctcCompress simply route URLs to ctcCompress.php rather than your front end assets.  In apache this can be done with an .htaccess file; there's an example of this below.

## Example

The line below will let ctcCompress handle all requests to GIF, JPG & PNG files in the images folder.

    rewriterule ^images/([a-zA-Z0-9-_+.]+).(gif|jpg|png)$ /ctcCompress.php [L]

## Acknowledgements

This project was started by [Andrew G. Johnson](https://github.com/andrewgjohnson), contact via [Twitter](http://twitter.com/andrewgjohnson), [Email](mailto:andrew@andrewgjohnson.com), [GitHub](https://github.com/andrewgjohnson) or [Online](http://www.andrewgjohnson.com/)

## Changelog

######v1.0.1 (November 16, 2013)
 * Fix for URLs containing a query string
 * Added individual license file

######v1.0.0 (May 1, 2013)
 * Intial release of ctcCompress