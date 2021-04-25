# Shi-Painter Pro HTML5 Port

## Files
* `spainter.js` is a copy of `sptr1114/spainter.js`
* `index.html` is a modified version of `sptr1114/spainter_pro.html`

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

3. Run `cheerpjfy.py` on `spainter.jar`. This generates ` spainter.jar.js` (which looks pretty human *un*readable).
4. Start a web server with Python:
```
python3 -m http.server
```
5. Go to `http://localhost:8000/`. Shi-painter should load!

Unfortunately, the pro features might not work. I get this error in the Javascript console:
```
GET http://localhost:8000/res/pro.zip.js net::ERR_ABORTED 404 (File not found)
cjLoadScript	@	loader.js:306
_m4Vanpn3cGKuPdWaruT9arbsdrrsqCTNnn	@	rt.jar.js:298
_m4Vanpn3cGKuPdWaruD9arbsdrrsmSx9o	@	rt.jar.js:324
_h4FcIhzWSea$0eClassLoader11defingjHWE2	@	rt.jar.js:934
_h4pbS6O1URLClassLoader11definefjXWE12	@	rt.jar.js:674
_h4pbS6y3URLClassLoader10access$100E21	@	rt.jar.js:674
_h4pbScP1URLClassLoader$13runE1	@	rt.jar.js:677
_h4pbScP1URLClassLoader$13runE2	@	rt.jar.js:677
_h4FcIdz4AccessController12doPrivilegedE11	@	rt.jar.js:817
_h4pbS64YURLClassLoaeKfXfindf9GWE9	@	rt.jar.js:674
eval	@	VM6803:3
runContinuationStack	@	loader.js:387
cheerpjSchedule
```

I probably have to compile `pro.zip` into Javascript, but for some reason `pro.zip` is not a `.jar` file...


## Sources
* [Getting Started (Cheerpj)](https://github.com/leaningtech/cheerpj-meta/wiki/Getting-Started)
