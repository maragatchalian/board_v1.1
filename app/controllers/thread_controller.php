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

    //Write the comments
    public function write() {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment;
        $page = Param::get('page_next');
            
        switch ($page) {
            case 'write_end':            
            $comment->username = Param::get('username');
            $comment->body = Param::get('body');
            $thread->write($comment);
            break;
                
        default:
            throw new NotFoundException("{$page} is not found");    
        break;
        }
                        
   $this->set(get_defined_vars());            
   $this->render($page);
    }    
} //end

