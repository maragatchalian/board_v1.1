<?php
class ThreadController extends AppController {

    const MAX_THREAD_PER_PAGE = 7;
    const MAX_COMMENT_PER_PAGE = 5;
    /*
    * Create new thread
    * :: - STATIC FUNCTION, can be called from the class name
    * -> - INSTANCE, can only be called from an instance of the class.
    */

    /*
    *   Everything inputted on the form (view/thread/create.php) will be 
    *   gathered by this function
    */
    public function create() {
        $thread = new Thread();
        $comment = new Comment();
        $current_page = Param::get('page_next', 'create');   
                
            switch ($current_page) { 
            case 'create':
            break;
      
        /*  
        *   After the user clicked on submit, the page will be redirected to 'create_end'
        *   From the $thread database, this will get the title.. and so on. 
        *   after all, controllers are all about getting the inputted data.
        *   then the data gathered here will be tranferred to view (view/thread/view.php)
        */
        case 'create_end':
            $thread->title = Param::get('title'); 
            $thread->category_name = Param::get('category_name'); 
            $comment->username = Param::get('username'); 
            $comment->body = Param::get('body');
            
              try 
            {
                $thread->create($comment);
            } catch (ValidationException $e) {
                $current_page = 'create';
            }
              break;
            default:
                throw new NotFoundException("{$current_page} is not found");
            break;
        }  

        $this->set(get_defined_vars());
        $this->render($current_page);
                    
    }
    
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

            try {            
               $thread->write($comment);
            } catch (ValidationException $e) {                    
               $page = 'write';
            }                        
           break;
                
        default:
            throw new NotFoundException("{$page} is not found");    
        break;
        }
                        
   $this->set(get_defined_vars());            
   $this->render($page);
    }    
} //end

