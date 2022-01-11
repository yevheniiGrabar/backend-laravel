<?php

namespace App\Admin\Controllers;

use App\Models\Locale;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LocaleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Locale';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Locale());

        $grid->column('id', __('Id'));
        $grid->column('group', __('Group'));
        $grid->column('key', __('Key'));
        $grid->column('text', __('Text'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Locale::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('group', __('Group'));
        $show->field('key', __('Key'));
        $show->field('text', __('Text'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Locale());

        $form->text('group', __('Group'));
        $form->text('key', __('Key'));
        $form->keyValue('text', __('Text'));

        return $form;
    }
}
