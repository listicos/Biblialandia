<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TrophyDescriptionController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for trophy_description
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "TrophyDescription", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $trophy_description = TrophyDescription::find($parameters);
        if (count($trophy_description) == 0) {
            $this->flash->notice("The search did not find any trophy_description");

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $trophy_description,
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
     * Edits a trophy_description
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $trophy_description = TrophyDescription::findFirstByid($id);
            if (!$trophy_description) {
                $this->flash->error("trophy_description was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "trophy_description",
                    "action" => "index"
                ));
            }

            $this->view->id = $trophy_description->id;

            $this->tag->setDefault("id", $trophy_description->id);
            $this->tag->setDefault("trophy_id", $trophy_description->trophy_id);
            $this->tag->setDefault("language_id", $trophy_description->language_id);
            $this->tag->setDefault("name", $trophy_description->name);
            $this->tag->setDefault("description", $trophy_description->description);
            
        }
    }

    /**
     * Creates a new trophy_description
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "index"
            ));
        }

        $trophy_description = new TrophyDescription();

        $trophy_description->trophy_id = $this->request->getPost("trophy_id");
        $trophy_description->language_id = $this->request->getPost("language_id");
        $trophy_description->name = $this->request->getPost("name");
        $trophy_description->description = $this->request->getPost("description");
        

        if (!$trophy_description->save()) {
            foreach ($trophy_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "new"
            ));
        }

        $this->flash->success("trophy_description was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy_description",
            "action" => "index"
        ));

    }

    /**
     * Saves a trophy_description edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $trophy_description = TrophyDescription::findFirstByid($id);
        if (!$trophy_description) {
            $this->flash->error("trophy_description does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "index"
            ));
        }

        $trophy_description->trophy_id = $this->request->getPost("trophy_id");
        $trophy_description->language_id = $this->request->getPost("language_id");
        $trophy_description->name = $this->request->getPost("name");
        $trophy_description->description = $this->request->getPost("description");
        

        if (!$trophy_description->save()) {

            foreach ($trophy_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "edit",
                "params" => array($trophy_description->id)
            ));
        }

        $this->flash->success("trophy_description was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy_description",
            "action" => "index"
        ));

    }

    /**
     * Deletes a trophy_description
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $trophy_description = TrophyDescription::findFirstByid($id);
        if (!$trophy_description) {
            $this->flash->error("trophy_description was not found");

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "index"
            ));
        }

        if (!$trophy_description->delete()) {

            foreach ($trophy_description->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "trophy_description",
                "action" => "search"
            ));
        }

        $this->flash->success("trophy_description was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "trophy_description",
            "action" => "index"
        ));
    }

}
