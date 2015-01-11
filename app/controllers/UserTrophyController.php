<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserTrophyController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user_trophy
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "UserTrophy", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $user_trophy = UserTrophy::find($parameters);
        if (count($user_trophy) == 0) {
            $this->flash->notice("The search did not find any user_trophy");

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $user_trophy,
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
     * Edits a user_trophy
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user_trophy = UserTrophy::findFirstByid($id);
            if (!$user_trophy) {
                $this->flash->error("user_trophy was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "user_trophy",
                    "action" => "index"
                ));
            }

            $this->view->id = $user_trophy->id;

            $this->tag->setDefault("id", $user_trophy->id);
            $this->tag->setDefault("user_id", $user_trophy->user_id);
            $this->tag->setDefault("trophy_id", $user_trophy->trophy_id);
            $this->tag->setDefault("created_at", $user_trophy->created_at);
            $this->tag->setDefault("updated_at", $user_trophy->updated_at);
            
        }
    }

    /**
     * Creates a new user_trophy
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "index"
            ));
        }

        $user_trophy = new UserTrophy();

        $user_trophy->user_id = $this->request->getPost("user_id");
        $user_trophy->trophy_id = $this->request->getPost("trophy_id");
        $user_trophy->created_at = $this->request->getPost("created_at");
        $user_trophy->updated_at = $this->request->getPost("updated_at");
        

        if (!$user_trophy->save()) {
            foreach ($user_trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "new"
            ));
        }

        $this->flash->success("user_trophy was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_trophy",
            "action" => "index"
        ));

    }

    /**
     * Saves a user_trophy edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user_trophy = UserTrophy::findFirstByid($id);
        if (!$user_trophy) {
            $this->flash->error("user_trophy does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "index"
            ));
        }

        $user_trophy->user_id = $this->request->getPost("user_id");
        $user_trophy->trophy_id = $this->request->getPost("trophy_id");
        $user_trophy->created_at = $this->request->getPost("created_at");
        $user_trophy->updated_at = $this->request->getPost("updated_at");
        

        if (!$user_trophy->save()) {

            foreach ($user_trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "edit",
                "params" => array($user_trophy->id)
            ));
        }

        $this->flash->success("user_trophy was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_trophy",
            "action" => "index"
        ));

    }

    /**
     * Deletes a user_trophy
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $user_trophy = UserTrophy::findFirstByid($id);
        if (!$user_trophy) {
            $this->flash->error("user_trophy was not found");

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "index"
            ));
        }

        if (!$user_trophy->delete()) {

            foreach ($user_trophy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "user_trophy",
                "action" => "search"
            ));
        }

        $this->flash->success("user_trophy was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "user_trophy",
            "action" => "index"
        ));
    }

}
