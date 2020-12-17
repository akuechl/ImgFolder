# ImgFolder [![Build Status](https://travis-ci.com/akuechl/ImgFolder.svg?branch=master)](https://travis-ci.com/akuechl/ImgFolder) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/c875fa21969246d5b9706690a25f4bbf)](https://www.codacy.com/app/github-ariel/ImgFolder)

ImgFolder is a simple Joomla Plugin to print out all images of a folder.

Usage
--------------

Add the image folder as attribute data-folder-loop and the content of this element will be repeated over all files.

I.e. add:

```html
{ImgFolder 'images/stories/topic-1'}<img src="[0]" height="150" alt="" />{/ImgFolder}
```

Result:

```html
<img src="images/stories/topic-1/image1.jpg" height="150" alt="" />
<img src="images/stories/topic-1/image2.jpg" height="150" alt="" />
<img src="images/stories/topic-1/image3.jpg" height="150" alt="" />
<img src="images/stories/topic-1/image4.jpg" height="150" alt="" />
```

Open Issue
-----------------

At the moment you can add one folder per page only. The reason is you need an unique replacement part. To fix this issue make all replacements unique.

For example with a dummy class:

```html
{ImgFolder 'images/stories/topic-1'}<img class="_1" src="[0]" height="150" alt="" />{/ImgFolder}
{ImgFolder 'images/stories/topic-2'}<img class="_2" src="[0]" height="150" alt="" />{/ImgFolder}
```
