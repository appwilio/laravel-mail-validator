<?php

namespace App\Http\Controllers;

use App\Domain\Email\Email;
use App\Domain\Email\EmailRepository;
use App\Http\Requests\UploadRequest;

class UploadController extends Controller
{
    /**
     * @type EmailRepository
     */
    protected $emails;

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;
    }


    public function showForm(){
        return view('upload');
    }

    public function doUpload(UploadRequest $request){
        $file = $request->file("file")->openFile("r");
        $add = 0;
        while ($line = $file->fgetcsv(",")) {
            $email = sanitize_email($line[0]);

            if(!$email) {
                continue;
            }

            if($this->emails->getFirstBy("address", $email)) {
               continue;
            }

            $newEmail = new Email([
                "address" => $line[0]
            ]);
            $newEmail->save();

            $add++;
        }
        return redirect()->route("upload.form")->with("added", $add);
    }
}
