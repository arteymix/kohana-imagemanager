# Image manager for Kohana

It stores images a similar way git does : it uses the sha1 as name.

It is designed to work closely to models so I have backed it in database.

It works for both multiple and single file.

To store an image :

    $images = Images::instance()->store($_FILES['images']);
    
It will return a list of ORM models. You may add them in relation with your own models.

    $user->add('images', $images);
    
If deleting an image, unlink on the file will be triggered automatically.

    $image->delete();
