<h1>All threads</h1>

<!--eh($string)
    function from app/helpers/html_helper.php 
    where it converts special chars to HTML entities.
-->
<ul>
  <?php foreach ($threads as $v):?>
    <li> 
        <a href="<?php eh(url('thread/view', array('thread_id' => $v->id)))?>">
            <?php eh($v->title)?>
        </a>
    </li>
    <?php endforeach ?>
</ul>

<br />
<!--Create Thread button-->
<a class="btn btn-large btn-medium btn-info" href="<?php eh(url('thread/create')) ?>">Create</a>