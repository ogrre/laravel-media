# Laravel Media

### Documentation

1. Installation:
    1. Provider
        - in config/app.php
            ``'providers' => [
           // ...
           Ogrre\Media\MediaServiceProvider::class,
           ];``
        - in terminal, publish migration and config file
           ```php artisan vendor:publish --provider="Ogrre\Media\MediaServiceProvider```
    2. Migrations
        - php artisan migrate
2. Configuration
    1. Config file is copying /config/media.php
    2. Default value (if Media is null when you store media_file )
3. Medias
    1. Default media (config)
    2. Create media 
        - Media::create([« name => ‘avatar’, …])
    3. Retrieve media per
        1. Model
        2. Name: findByName(‘avatar’) 
        3. Id (eloquent)
    4. Update media
        - Media::udpate([« name => ‘avatar’, …])
    5. Delete media(cascade)
        - Media::destroy(id)
4. Media File
    1. Store media file
        - User->storeMedia($file, ’nom du media’ = null)
    2. Retrieve media file per
        1. Model 
            - User->medias renvoie tous les media_files where model_id =  model_id (many to one)
        2. Media Name
            - User->getMedia(’nom du media’) renvoie le media_file where mediatype = ‘nom du media’  (si aucun media, media par defaut) 
                - Example: User->media(‘avatar’)->path
                - Accept Media object or String 
        3. Id (eloquent)
    3. Update media file (change media_file or media by other)
        - User->updateMedia($file, ’nom du media’ = null) verifier que le media_file existe et que le media correspond 
    4. Delete media file
        - User->removeMedia(’nom du media’)
5. Model hasMedia
    1. Retrieve Model per Media (User::media(‘avatar’)->get()
    2. Determine if model has media (user->hasMedia(‘avatar’) true if media file exist where media->name == ‘avatar’)
6. Database Seeding
    1. 
