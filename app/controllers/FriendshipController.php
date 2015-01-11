<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class FriendshipController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for friendship
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Friendship", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $friendship = Friendship::find($parameters);
        if (count($friendship) == 0) {
            $this->flash->notice("The search did not find any friendship");

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $friendship,
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
     * Edits a friendship
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $friendship = Friendship::findFirstByid($id);
            if (!$friendship) {
                $this->flash->error("friendship was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "friendship",
                    "action" => "index"
                ));
            }

            $this->view->id = $friendship->id;

            $this->tag->setDefault("id", $friendship->id);
            $this->tag->setDefault("user_id", $friendship->user_id);
            $this->tag->setDefault("friend_id", $friendship->friend_id);
            $this->tag->setDefault("created_at", $friendship->created_at);
            $this->tag->setDefault("type", $friendship->type);
            
        }
    }

    /**
     * Creates a new friendship
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "index"
            ));
        }

        $friendship = new Friendship();

        $friendship->user_id = $this->request->getPost("user_id");
        $friendship->friend_id = $this->request->getPost("friend_id");
        $friendship->created_at = $this->request->getPost("created_at");
        $friendship->type = $this->request->getPost("type");
        

        if (!$friendship->save()) {
            foreach ($friendship->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "new"
            ));
        }

        $this->flash->success("friendship was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "friendship",
            "action" => "index"
        ));

    }

    /**
     * Saves a friendship edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $friendship = Friendship::findFirstByid($id);
        if (!$friendship) {
            $this->flash->error("friendship does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "index"
            ));
        }

        $friendship->user_id = $this->request->getPost("user_id");
        $friendship->friend_id = $this->request->getPost("friend_id");
        $friendship->created_at = $this->request->getPost("created_at");
        $friendship->type = $this->request->getPost("type");
        

        if (!$friendship->save()) {

            foreach ($friendship->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "edit",
                "params" => array($friendship->id)
            ));
        }

        $this->flash->success("friendship was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "friendship",
            "action" => "index"
        ));

    }

    /**
     * Deletes a friendship
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $friendship = Friendship::findFirstByid($id);
        if (!$friendship) {
            $this->flash->error("friendship was not found");

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "index"
            ));
        }

        if (!$friendship->delete()) {

            foreach ($friendship->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "friendship",
                "action" => "search"
            ));
        }

        $this->flash->success("friendship was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "friendship",
            "action" => "index"
        ));
    }

}
