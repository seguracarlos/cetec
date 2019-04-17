<?php
namespace Expenses\Services;

use Expenses\Model\ExpensesModel;

class ExpensesServices
{
	private $expensesModel;
	
	/* Instancia del modelo de gastos*/
	public function getExpensesModel()
	{
		return $this->expensesModel = new ExpensesModel();
	}
	
	public function fetchAll()
	{
		$expenses = $this->getExpensesModel()->fetchAll();
		return $expenses;
	}
	
	public function getExpensesById($id_expense)
	{
		$expense = $this->getExpensesModel()->getExpensesById($id_expense);
		return $expense;
	}
	
	public function addExpenses($formData)
	{	
		//echo "<pre>"; print_r($formData); exit;
		$data = array(
				"date_of_expenses"        => $formData['date_of_expenses'],
				"amount_of_expenses"      => $formData['amount_of_expenses'],
				"description_of_expenses" => $formData['description_of_expenses'],
				"reference_of_expenses"   => $formData['reference_of_expenses'],
				"expenses_type"           => $formData['expenses_type'],
				"id_fk_user"              => $formData['user_id']
		);
		$addExpense = $this->getExpensesModel()->addExpenses($data);
		return $addExpense;
	}
	
	//Editar un gasto
	public function editExpenses($formData, $id_expense)
	{
		$data = array(
				"date_of_expenses"        => $formData['date_of_expenses'],
				"amount_of_expenses"      => $formData['amount_of_expenses'],
				"description_of_expenses" => $formData['description_of_expenses'],
				"reference_of_expenses"   => $formData['reference_of_expenses'],
				"expenses_type"           => $formData['expenses_type']
		);

		$editExpense = $this->getExpensesModel()->editExpenses($data, $id_expense);
		return $editExpense;
	}
	
	//Eliminar un usuario
	public function deleteUser($id_user)
	{
		$delete_user = $this->getInventoryModel()->deleteUser($id_user);
		return $delete_user;
	}
}