<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 2016/1/12
 * Time: 15:11
 */

namespace Lib\Builder;


class BuilderLeader
{
    protected $builders = array();

    public function __construct()
    {

    }

    public function add(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    public function build()
    {
        foreach($this->builders as $builder)
        {
            if($builder->getEnable()>0)
            {
                $builder->builder();
            }
        }
        return 1;
    }


}