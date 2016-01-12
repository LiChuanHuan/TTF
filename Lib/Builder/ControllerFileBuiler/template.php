<?php

namespace Admin\Controller;

use Common\Controller\AdminDataTableController;

/**
 * {t:controller_name}後台控制器.
 */
class {t:controller_name}Controller extends AdminDataTableController
{
    protected $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = D('Common/{t:controller_name}');
        $this->setUseModel('Common/{t:controller_name}');
    }

    /**
     * {t:controller_name}列表显示页面.
     */
    public function {t:table_name}_index()
    {
        $this->display();
    }



    /**
     * {t:controller_name}新增页面.
     */
    public function add()
    {
        $model = $this->model->select();
        $this->assign('{t:controller_name}', $model);
        $this->display();
    }

    /**
     * {t:controller_name}新增方法.
     */
    public function add_post()
    {
        if (IS_POST) {
            if ($this->model->create()) {
                $result = $this->model->add();
                if ($result !== false) {
                    $this->success('添加成功！', U('{t:controller_name}/{t:table_name}_index'));
                } else {
                    $this->error('添加失败！');
                }
            } else {
                $this->error($this->model->getError());
            }
        }
    }

    /**
     * {t:controller_name}编辑页面.
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
     * {t:controller_name}编辑方法.
     */
    public function edit_post()
    {
        if (IS_POST) {
            $id = intval(I('post.id'));
            $model = $this->model->where(array('id' => $id))->find();

            $data = array();
            {t:post_fields}
            if ($model) {
                $result = $this->model->save($data);
                if ($result) {
                    $this->success('{t:controller_name}修改成功！', U('{t:controller_name}/{t:table_name}_index'));
                } else {
                    $this->error('{t:controller_name}修改失败！');
                }
            } else {
                $this->error($this->model->getError());
            }
        }
    }

    /**
     *  {t:controller_name}删除方法.
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
