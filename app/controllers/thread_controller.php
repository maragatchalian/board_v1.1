<?php
class ThreadController extends AppController {
    
    //Will display the list of threads
    public function index() {
        $threads = Thread::getAll();
            //create an instance of the Thread model, 
            //and call its static function getAll()
        $this->set(get_defined_vars());
            //will set all defined vars to its view (thread/index)
    }

    //will view comments on each thread
    public function view() {
        $thread = Thread::get(Param::get('thread_id'));
        $comments = $thread->getComments();

            //^^get the thread model, based on the thread_id
            //The function passes the variable 
            //‘$thread’ which contains the thread model object.
        $this->set(get_defined_vars());

    }
}

