# Shi-Painter Pro HTML5 Port

## Files
* `index.html` is a modified version of `sptr1114/spainter_pro.html`
* `spainter_all.jar` is a copy of `wacpoteto-1.6.7/oekaki/spainter_all.jar`
    * With the `_all` version of this file we don't need to worry about passing in that extra `pro.zip` or compiling that file as a dependency -whew-
* `sp.js.jar` is a copy of `sptr1114/sp.js.jar`

## Compile .jar to Javascript
1. Download Java to Javascript compiler [CheerpJ](https://leaningtech.com/cheerpj/) (the same developers who made the [Chrome extension](https://chrome.google.com/webstore/detail/cheerpj-applet-runner/bbmolahhldcbngedljfadjlognfaaein/related)).
2. The `spainter.jar` was missing some Manifest file, which `cheerpjfy.py` does not fully account for. So I added a `try: except` statement in the `cheerpjfy.py`:
```
336 def getManifestProperty(jarFile, propertyName):
337         # Get information from the manifest if any
338         try:
339                 m = jarFile.open("META-INF/MANIFEST.MF")
340                 if m == None:
341                         return None
342         except KeyError:
343                 print('Failed to read Manifest file.')
344                 return None
```

(they used tabs... 😑)

which gets the compilation to work... From the first `if` statement `if m == None`, it seems ok if the Manifest file was missing.

3. Run `cheerpjfy.py` on `spainter_all.jar`. This generates ` spainter_all.jar.js` (which looks pretty human *un*readable).
4. Start a web server with Python:
```
python3 -m http.server
```
5. Go to `http://localhost:8000/`. Shi-painter should load!


## Sources
* [Getting Started (Cheerpj)](https://github.com/leaningtech/cheerpj-meta/wiki/Getting-Started)
