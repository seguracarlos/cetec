#Osiris by IOFractal
#Este script crea la BD de Osiris

if [ -d logs ]; then
     echo "Existe."
else
     echo "Crear directorio para logs"
     mkdir logs
fi

if [ ! -d /var/run/mysqld ]; then
    echo "Crear acceso BD."
    sudo mkdir /var/run/mysqld
	sudo ln -s /opt/lampp/var/mysql/mysql.sock /var/run/mysqld/mysqld.sock
fi

DAT=$(date +"%Y-%m-%d-%H%M%S")
LOGFILE="$DAT-bdlog.log"
touch ./logs/$LOGFILE

echo $(date) + "Inicia - 0_ Borrando entidades de la BD" 
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/0_db_script_clean.sql
echo Recorded at $DAT $(date) Delete Script File >> ./logs/$LOGFILE 
echo $(date) + "Fin - 0_ Borrando entidades de la BD" 

echo $(date) + "Inicia - 1_ Creando entidades" 
echo Espere...
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_category.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acc_receivable.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_permissions.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_resources.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_roles.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_activities.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_activityDates.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_addresses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_articles_of_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_bank_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_bank.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_banks.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_banks_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_books.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_category.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_clock.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_company_contact.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_company.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_components.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_contents.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_contract_types.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cron_token.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_curses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_curses_employee.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_curses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_job_experience.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_jobexperience_tech.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_job_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_language_name.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_language.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_technologies.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_tech.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_data_meeting.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_data.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_datepaymentsorder.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_dates_of_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_days.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_department.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_district.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents_fields_content.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents_fields.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_entry_out_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_entry_out_product.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_expenses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_ho_mondays.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_invoices.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_permission.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_role_permission.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_user_role.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_resource.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_role.sql            
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_job_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_keys.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_liabilities.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_link.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_made_payments_order.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_made_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_measuring_unit.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_minutesbyuser.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_minutes.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_neighborhood.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_num.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_pay_payroll.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_preferences.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_price_products.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_price_services.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_products.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_project_contact.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_project_role.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_projects.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase2.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase_invoices.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase_order.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_saleproducts.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_sales.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_services.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_shoppingcart.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_state_of_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_states_of_mexico.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_stock_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_stock.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_technologies.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_techtype.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_themes.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tipo_books.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tipo_tutorial.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_topic.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tutorial.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_types.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_update_actions.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_user_details.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_users_addresses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_users_projects.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_vision.sql
echo Recorded at $DAT $(date) Creando entidades >> ./logs/$LOGFILE 
echo $(date) + "Fin - 1_ Creando entidades" 	

echo $(date) + "Inicia - 2_ Insertando datos en entidades ACL Osiris"
echo Espere...
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/1_B_osiris_data_iof_role_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/2_B_osiris_data_iof_users_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/3_B_osiris_data_iof_resource_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/4_B_osiris_data_iof_permission_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/5_B_osiris_data_iof_user_role_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/6_B_osiris_data_iof_role_permission_enlasa.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/7_B_osiris_data_varios_enlasa_enlasa.sql
echo Recorded at $DAT $(date) Insertando datos en entidades ACL Osiris >> ./logs/$LOGFILE
echo $(date) + "Fin - 2_ Insertando datos en entidades ACL Osiris"

echo $(date) + "Inicia - 3_ Insertando datos en entidades"
echo Espere...
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_category.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_acc_receivable.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_activities.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_activityDates.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_addresses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_articles_of_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_bank_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_bank.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_banks.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_banks_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_books.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash_current_balance.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash_tx.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_category.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_clock.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_company_contact.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_company.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_components.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_contents.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_contract_types.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cron_token.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_curses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_curses_employee.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_curses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_job_experience.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_jobexperience_tech.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_job_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_language_name.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_language.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_technologies.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_tech.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_data_meeting.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_data.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_datepaymentsorder.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_dates_of_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_days.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_department.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_district.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents_fields_content.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents_fields.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_entry_out_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_entry_out_product.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_expenses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_ho_mondays.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_invoices.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_job_users.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_keys.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_liabilities.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_link.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_made_payments_order.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_made_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_measuring_unit.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_minutesbyuser.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_minutes.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_neighborhood.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_num.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_payments.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_pay_payroll.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_preferences.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_price_products.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_price_services.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_products.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_project_contact.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_project_role.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_projects.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase2.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase_invoices.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase_order.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_saleproducts.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_sales.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_services.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_shoppingcart.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_state_of_inventories.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_states_of_mexico.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_stock_merchandize.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_stock.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_technologies.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_techtype.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_themes.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tipo_books.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tipo_tutorial.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_topic.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tutorial.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_types.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_update_actions.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_user_details.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_users_addresses.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_users_projects.sql
mysql -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_vision.sql
echo Recorded at $DAT $(date) Insertando datos en entidades >> ./logs/$LOGFILE
echo $(date) + "Fin - 3_ Insertando datos en entidades"