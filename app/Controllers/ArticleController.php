<?php

namespace App\Controllers;

use PDO;
use App\Presenters\ArticlePresenter;
use App\Presenters\ErrorPresenter;

class ArticleController extends Controller
{
    protected $fillable = ['title', 'body'];

    public function get($id)
    {
        $article = $this->getArticleById($id);

        if (!$article) {
            return $this->response(null, 404);
        }

        return $this->response((new ArticlePresenter($article))->present(), 200);
    }

    public function post()
    {
        $payload = $this->request->getParsedBody();

        if(!isset($payload['title'], $payload['body']))
        {
            return $this->response((new ErrorPresenter(
                [ 'message' => 'Title and body required' ]
            ))->present(), 400);
        }

        $create = $this->container->get('db')->prepare("
            INSERT INTO articles (title, body)
            VALUES (:title, :body)
        ");

        $create->execute([
            'title' => $payload['title'],
            'body' => $payload['body'],
        ]);

        $article = $this->getArticleById($this->container->get('db')->lastInsertId());

        return $this->response(
            (new ArticlePresenter($article))->present(),
            200
        );
    }

    public function put($id)
    {
        $payload = $this->request->getParsedBody();

        $toFill = array_filter(array_map(function ($column) {
            if(in_array($column, $this->fillable))
            {
                return "{$column} = :{$column}";
            }
        }, array_keys($payload)));

        if(empty($toFill))
        {
            return $this->response((new ErrorPresenter([
                'message' => 'No columns selected to update',
            ]))->present(), 400);
        }

        $update = $this->container->get('db')->prepare("
            UPDATE articles
            SET " . implode(', ', $toFill) . "
            WHERE id = :id
        ");

        $update->execute(
            array_merge(['id' => $id], $payload)
        );

        if(!$update)
        {
            return $this->response((new ErrorPresenter([
                'message' => 'Article not updated',
            ]))->present(), 500);
        }

        $article = $this->getArticleById($id);

        if(!$article)
        {
            return $this->response(null, 404);
        }

        return $this->response(
            (new ArticlePresenter($article))->present(),
            200
        );
    }

    public function delete($id)
    {
        $delete = $this->container->get('db')->prepare("
            DELETE FROM articles
            WHERE id = :id
        ");

        $delete->execute(['id' => $id]);

        if(!$delete)
        {
            return $this->response((new ErrorPresenter([
                'message' => 'Article not deleted',
            ]))->present(), 500);
        }

        if(!$delete->rowCount())
        {
            return $this->response(null, 404);
        }

        return $this->response(null, 200);
    }

    protected function getArticleById($id){
        $article = $this->container->get('db')->prepare("
            SELECT *
            FROM articles
            WHERE id = :id
        ");

        $article->execute(['id' => $id]);
        return $article->fetch(PDO::FETCH_OBJ);
    }
}
