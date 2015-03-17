<?php

class Thread extends AppModel {

    public static function getAll() {
        $threads = array();
        $db=DB::conn(); //connect to database

        //returns an array of multiple records from the table
        $rows = $db->rows('SELECT * from thread01');

        foreach ($rows as $row) {
            $threads[] = new Thread($row); 
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
           throw new RecordNotFoundException('no record found');
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
        $db = DB::conn();
        $db->query(                
         'INSERT INTO comment01 SET thread_id = ?, username = ?, body = ?, created = NOW()',        
           array($this->id, $comment->username, $comment->body)
        );                    
   } 
} //end



