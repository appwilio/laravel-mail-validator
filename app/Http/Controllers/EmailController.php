<?php

namespace App\Http\Controllers;

use App\Domain\Email\EmailRepository;
use Illuminate\Http\Request;

use App\Http\Requests;

class EmailController extends Controller
{
    /**
     * @type EmailRepository
     */
    protected $emails;

    public function __construct(EmailRepository $emails)
    {
        $this->emails = $emails;
    }

    public function index() {
        return view("list.emails");
    }

    public function paginate(){
        return $this->emails->paginate();
    }
}
