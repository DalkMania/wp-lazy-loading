WP Simple Lazy Loading
===============

A Simple Lazy Loading Functionality Plugin for WordPress. Built with [lozad.js][lozad.js]. 

## Installing

1. Upload this repo (or git clone) to your plugins folder and activate it.

## Usage

Just activate the plugin and enjoy the Lazy Loading of all content and thumbnail type images.

If you want to make the loading a little bit prettier, add something like this to your stylesheet:

```
.lazy-load {
    transition: opacity .15s;
    opacity: 0;
}

.lazy-load.is-loaded {
    opacity: 1;
}
```

[lozad.js]: https://github.com/ApoorvSaxena/lozad.js