<?php namespace App\Http\Controllers;

use App\Models\Article;
use Roocket\PocketCore\Controller;
use Roocket\PocketCore\Request;

class ArticleController extends Controller
{
    public function index() : string
    {
      
      
    }

    public function create()
    {
        return $this->render('articles.create', [
            'title' => 'hello roocket',
            'auth' => false
        ]);

        

    }


    public function storeArticles()
    {
        
        $article = (new Article());

        $article->create([

            "title" => "this is articles one" ,

            "body" => "This is articles body"

        ]);

        return 'articles created';
    }
   

    public function store(Request $request)
    {
        $validation = $this->validate($request->all() , [
            'title' => 'required|min:10'
        ]);

        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors();
            echo "<pre>";
            print_r($errors->firstOfAll());
            echo "</pre>";
            exit;
        } else {
            // validation passes
            echo "Success!";
        }

        return $request->query('id');
    }

    public function articlesUpdate()
    {
        
        $article = (new Article());

        $article->update((int) 1,[

            "title" => "this is not an title"

        ]);

        return "articles update";

    }

    public function deleteArticles()
    {   
        
        // ability to delete 
        $deletingUsers = [3,4,5];

        foreach($deletingUsers as $deleteId) {

        (new Article())->delete( $deleteId);

        }

        // delete single recored
        (new Article())->delete( $deleteId);



        return "the articles deleted sucessfully";
    }
 

  

}
