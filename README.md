# Laravel Media

### Introduction
This Laravel package allows you to associate media with models to be able to manage your files more easily.

This is my first package, do not hesitate to report problems, I may not have thought of everything

### Documentation, Installation, and Usage Instructions
See the [documentation](https://jbloup.dev) for detailed installation and usage instructions.

#### Installation
This package publishes a config/media.php file. If you already have a file by that name, you must rename or remove it.

You can install the package via composer:
```bash
composer require ogrre/laravel-media
```

The service provider is automatically registered in app.php, but you can add the service provider manually in config/app.php
```
# config/app.php

'providers' => [
    // ...
    Ogrre\Media\MediaServiceProvider::class,
];
```
You need to start the migration for this to work well, so make sure you have **media.php** in the config folder as well as the **create_media_tables.php** file in the **database/migrations** folder of your application. 

If this is not the case run the command
```bash
php artisan vendor:publish --provider="Ogrre\Media\MediaServiceProvider"
```

### Configuration
By default, the **Media** class will take as an attribute mime_type and disk the values of the media configuration file. You can change there values in **config/media.php**.


### Usages
This package works with two classes, the class **Media** and the class **MediaFile**, to manage the files it is necessary to associate a model, such as a user for example, with a media, such as *avatar* for example.
It is therefore necessary to create the *avatar* media first.

#### Media
The Media class works as an eloquent model of Laravel create, update,... etc

You can create the media as follows
```
Media::create(['name' => "new_media"]);
```

In this situation, the media will be created with the default attributes corresponding to the configuration file.

Otherwise, you can do it in the classic way
```
Media::create(['name' => "pdf_media", "mime_type" => "pdf", "disk" => "public"]);
```
Once the **Media** are created, you can associate them with a model that has the trait *hasMedia*.

For exemple *User* model
```
class User extends Authenticatable
{
    use HasFactory, HasMedia;
    ...
```

To associate Media at *User* model
```
$user = User::find(1)

// assign media by id
$user->assignMedia(1);

// assign media by name
$user->assignMedia("avatar);

// assign multipe media
$user->assignMedia(['avatar', 'new_media']);

// assign media with media object
$media = Media::find(1);
$user->assignMedia($media);
```

You can check if model has media
```
// return false if user hasn't this media
$user->hasMedia($media); 
```
#### MediaFile

When a media is associated with a model, you can add a file that will be associated with the model as well as the media, it works like a classic crud.
```
public function upload(Request $request)
{
    $file = $request->file('avatar');

    $user = User::find(1);

    $user->addMediaFile($file, 'avatar');
}    
```
*getMediaFile* function will return the *MediaFile* model with all the useful data about the file.
```
$user->getMediaFile('avatar');  
```

*updateMediaFile* function will delete the previously saved file and save a new one to the media disk.
```
$user->addMediaFile($file, $media);  
```

*deleteMediaFile* will delete the *Mediafile* associated to Model as well as file.
```
$user->deleteMediaFile(1);  
```


