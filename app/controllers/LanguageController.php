<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class LanguageController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for language
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Language", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $language = Language::find($parameters);
        if (count($language) == 0) {
            $this->flash->notice("The search did not find any language");

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $language,
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
     * Edits a language
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $language = Language::findFirstByid($id);
            if (!$language) {
                $this->flash->error("language was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "language",
                    "action" => "index"
                ));
            }

            $this->view->id = $language->id;

            $this->tag->setDefault("id", $language->id);
            $this->tag->setDefault("name", $language->name);
            $this->tag->setDefault("code", $language->code);
            
        }
    }

    /**
     * Creates a new language
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "index"
            ));
        }

        $language = new Language();

        $language->name = $this->request->getPost("name");
        $language->code = $this->request->getPost("code");
        

        if (!$language->save()) {
            foreach ($language->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "new"
            ));
        }

        $this->flash->success("language was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "language",
            "action" => "index"
        ));

    }

    /**
     * Saves a language edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $language = Language::findFirstByid($id);
        if (!$language) {
            $this->flash->error("language does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "index"
            ));
        }

        $language->name = $this->request->getPost("name");
        $language->code = $this->request->getPost("code");
        

        if (!$language->save()) {

            foreach ($language->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "edit",
                "params" => array($language->id)
            ));
        }

        $this->flash->success("language was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "language",
            "action" => "index"
        ));

    }

    /**
     * Deletes a language
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $language = Language::findFirstByid($id);
        if (!$language) {
            $this->flash->error("language was not found");

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "index"
            ));
        }

        if (!$language->delete()) {

            foreach ($language->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "language",
                "action" => "search"
            ));
        }

        $this->flash->success("language was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "language",
            "action" => "index"
        ));
    }

}
