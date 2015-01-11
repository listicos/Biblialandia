<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class QuestionController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for question
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Question", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $question = Question::find($parameters);
        if (count($question) == 0) {
            $this->flash->notice("The search did not find any question");

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $question,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a question
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $question = Question::findFirstByid($id);
            if (!$question) {
                $this->flash->error("question was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "question",
                    "action" => "index"
                ));
            }

            $this->view->id = $question->id;

            $this->tag->setDefault("id", $question->id);
            $this->tag->setDefault("question", $question->question);
            $this->tag->setDefault("user_id", $question->user_id);
            $this->tag->setDefault("question_category_id", $question->question_category_id);
            $this->tag->setDefault("created_at", $question->created_at);
            $this->tag->setDefault("updated_at", $question->updated_at);
            $this->tag->setDefault("language_id", $question->language_id);
            
        }
    }

    /**
     * Creates a new question
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "index"
            ));
        }

        $question = new Question();

        $question->question = $this->request->getPost("question");
        $question->user_id = $this->request->getPost("user_id");
        $question->question_category_id = $this->request->getPost("question_category_id");
        $question->created_at = $this->request->getPost("created_at");
        $question->updated_at = $this->request->getPost("updated_at");
        $question->language_id = $this->request->getPost("language_id");
        

        if (!$question->save()) {
            foreach ($question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "new"
            ));
        }

        $this->flash->success("question was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question",
            "action" => "index"
        ));

    }

    /**
     * Saves a question edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $question = Question::findFirstByid($id);
        if (!$question) {
            $this->flash->error("question does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "index"
            ));
        }

        $question->question = $this->request->getPost("question");
        $question->user_id = $this->request->getPost("user_id");
        $question->question_category_id = $this->request->getPost("question_category_id");
        $question->created_at = $this->request->getPost("created_at");
        $question->updated_at = $this->request->getPost("updated_at");
        $question->language_id = $this->request->getPost("language_id");
        

        if (!$question->save()) {

            foreach ($question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "edit",
                "params" => array($question->id)
            ));
        }

        $this->flash->success("question was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question",
            "action" => "index"
        ));

    }

    /**
     * Deletes a question
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $question = Question::findFirstByid($id);
        if (!$question) {
            $this->flash->error("question was not found");

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "index"
            ));
        }

        if (!$question->delete()) {

            foreach ($question->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "question",
                "action" => "search"
            ));
        }

        $this->flash->success("question was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "question",
            "action" => "index"
        ));
    }

}
