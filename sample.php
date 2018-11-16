
    /**
     * Update the image.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateImage(Request $request)
    {

        if (\Auth::check()) {
            $user = \Auth::user();
        } else {
            return redirect('/login');
        }
        
        $accountID = request('accountID');
        $imageName = request('image_name');
        
        if (request('imageType') == 'photo') {
            //Store user Pic
            if (isset($imageName)) {                
                $picFilename = ImageUtils::storeImage($request, 'image_name', config('filesystems.user_pic_folder'), config('filesystems.user_thumbs_folder'));
            }
        } else {
            // Store user Logo
            if (isset($imageName)) {
                $logoFilename = ImageUtils::storeImage($request, 'image_name', config('filesystems.logo_pic_folder'), config('filesystems.logo_thumbs_folder'));
            }
        }

        
        
        if (isset($picFilename)) {
            $user->pic_name = $picFilename;
            $returnedFilePath = config('filesystems.google_path') . config('filesystems.user_thumbs_folder') . $picFilename;
        }
        if (isset($logoFilename)) {
            $user->logo_name = $logoFilename;
            $returnedFilePath = config('filesystems.google_path') . config('filesystems.logo_thumbs_folder') . $logoFilename;
        }
        
        try{
            $user->save();
         }
         catch(\Exception $e){
            return $e->getMessage();   
         }

         return $returnedFilePath;

    }
