# Shi-Painter Pro HTML5 Port

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

3. Run `cheerpjfy.py` on `spainter.jar`. This generates ` spainter.jar.js` (looks pretty human-*un*readable).

So now I'm not sure how to run this *thing*. I thought serving the `index.html` that I added from a web server was sufficient, but the page just gets stuck in the loading screen...

続く。。。
