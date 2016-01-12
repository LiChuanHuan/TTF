<?php

namespace Admin\Controller;

use Common\Controller\AdminDataTableController;

/**
 * User後台控制器.
 */
class UserController extends AdminDataTableController
{
    protected $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = D('Common/User');
        $this->setUseModel('Common/User');
    }

    /**
     * User列表显示页面.
     */
    public function user_index()
    {
        $this->display();
    }



    /**
     * User新增页面.
     */
    public function add()
    {
        $model = $this->model->select();
        $this->assign('User', $model);
        $this->display();
    }

    /**
     * User新增方法.
     */
    public function add_post()
    {
        if (IS_POST) {
            if ($this->model->create()) {
                $result = $this->model->add();
                if ($result !== false) {
                    $this->success('添加成功！', U('User/user_index'));
                } else {
                    $this->error('添加失败！');
                }
            } else {
                $this->error($this->model->getError());
            }
        }
    }

    /**
     * User编辑页面.
     */
    public function edit()
    {
        $id = intval(I('get.id'));
        $model = $this->model->where(array('id' => $id))->find();
        $this->assign('id', $id);
        $this->assign('model', $model);
        $this->display();
    }

    /**
     * User编辑方法.
     */
    public function edit_post()
    {
        if (IS_POST) {
            $id = intval(I('post.id'));
            $model = $this->model->where(array('id' => $id))->find();

            $data = array();
            	$data['id'] = I('post.id');
	$data['name'] = I('post.name');

            if ($model) {
                $result = $this->model->save($data);
                if ($result) {
                    $this->success('User修改成功！', U('User/user_index'));
                } else {
                    $this->error('User修改失败！');
                }
            } else {
                $this->error($this->model->getError());
            }
        }
    }

    /**
     *  User删除方法.
     */
    public function delete()
    {
        $id = intval(I('get.id'));
        if ($this->model->where("id=$id")->delete() !== false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }
}
