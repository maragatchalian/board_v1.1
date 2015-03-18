<?php

class Thread extends AppModel {

    const MIN_TITLE_LENGTH = 1;
    const MAX_TITLE_LENGTH = 30;

    //Thread Length Validation
    public $validation = array(
    'title' => array(
        'length' => array(
            'validate_between', self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH
            ),
        ),
    );


   //Create threads
    public function create(Comment $comment) {
        $this->validate();
        $comment->validate();
        
        if ($this->hasError() || $comment->hasError()) {
            throw new ValidationException('Invalid thread or comment');
        }

        $date_created = date("Y-m-d H:i:s");
        $params = array(            //$params is the variable name of this set. (title, created, category_name)
        'title' => $this->title,    //input will be stored in the column 'title'
        'created'=> $date_created,  //input will be stored in the column 'created'
        );
    //Latest inserted ID
    try {
        $db = DB::conn();
        $db->begin();
        $db->insert('thread01', $params); //insert $params (the previous set i mentioned before) into 'thread' table
        $this->id = $db->lastInsertId();
    //write first comment at the same time
        $this->write($comment);
        $db->commit();
        } catch (Exception $e) {
        $db->rollback();
        }
    }

    public static function getAll() {
        $threads = array();
        $db=DB::conn(); //connect to database

        //returns an array of multiple records from the table
        $rows = $db->rows('SELECT * from thread01');

        foreach ($rows as $row) {
            $threads[] = new self($row); 
            //^^create an array of object 'threads' from each row
        }
        return $threads;
    }

      public static function get($id){
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread01 WHERE id = ?', array($id));

        /*  validate if $row is not empty before 
        *   creating a new instance of Thread
        */
        if(!$row) {
           throw new RecordNotFoundException('No Record Found');
        }
        return new self($row);
    }

    //Select all comments from the database and send it to view.php
    public function getComments() {
        $comments = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM comment01 WHERE thread_id = ? ORDER BY created ASC', array($this->id));
                    
            foreach ($rows as $row) {                        
                $comments[] = new Comment($row);
             }        
       return $comments;
    }

    //write comments
    public function write(Comment $comment) {

        if (!$comment->validate()) {                        
           throw new ValidationException('invalid comment');
        }        

        $db = DB::conn();
        $db->query(                
         'INSERT INTO comment01 SET thread_id = ?, username = ?, body = ?, created = NOW()',        
           array($this->id, $comment->username, $comment->body)
        );                    
   } 


} //end



