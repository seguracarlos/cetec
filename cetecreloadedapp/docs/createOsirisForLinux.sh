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

OSIRISPATH="mysql"

DAT=$(date +"%Y-%m-%d-%H%M%S")
LOGFILE="$DAT-bdlog.log"
touch ./logs/$LOGFILE

echo $(date) + "Inicia - 0_ Borrando entidades de la BD" 
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/0_db_script_clean.sql
echo Recorded at $DAT $(date) Delete Script File >> ./logs/$LOGFILE 
echo $(date) + "Fin - 0_ Borrando entidades de la BD" 

echo $(date) + "Inicia - 1_ Creando entidades" 
echo Espere...
echo $(date) + "Inicia - osiris_table_states_of_mexico"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_states_of_mexico.sql
echo $(date) + "Fin - osiris_table_states_of_mexico"
echo $(date) + "Inicia - osiris_table_district"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_district.sql
echo $(date) + "Fin - osiris_table_district"
echo $(date) + "Inicia - osiris_table_neighborhood"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_neighborhood.sql
echo $(date) + "Fin - osiris_table_neighborhood"
echo $(date) + "Inicia -osiris_table_acc_receivable"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acc_receivable.sql
echo $(date) + "Fin -osiris_table_acc_receivable"
echo $(date) + "Inicia - osiris_table_category"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_category.sql
echo $(date) + "Fin - osiris_table_category" 
echo $(date) + "Inicia osiris_table_account"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account.sql
echo $(date) + "Fin - osiris_table_account"
echo $(date) + "Inicia table_account_category"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_category.sql
echo $(date) + "Fin table_account_category"
echo $(date) + "Inicia table_account_current_balance" 
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_current_balance.sql
echo $(date) + "Fin table_account_current_balance"  
echo $(date) + "Inicia -osiris_table_account_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_account_tx.sql
echo $(date) + "Fin -osiris_table_account_tx" 
echo $(date) + "Inicia - osiris_table_company"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_company.sql
echo $(date) + "Fin - osiris_table_company"
echo $(date) + "Inicia - osiris_table_job_users"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_job_users.sql
echo $(date) + "Fin - osiris_table_job_users"
echo $(date) + "Inicia - osiris_table_department"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_department.sql
echo $(date) + "Fin - osiris_table_department"
#echo $(date) + "Inicia -osiris_table_acl_permissions"
#$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_permissions.sql
#echo $(date) + "Fin - osiris_table_acl_permissions" 
#echo $(date) + "Inicia - osiris_table_acl_resources"
#$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_resources.sql
#echo $(date) + "Fin - osiris_table_acl_resources" 
#echo $(date) + "Inicia - osiris_table_acl_roles"
#$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_roles.sql
#echo $(date) + "Fin - osiris_table_acl_roles" 
#echo $(date) + "Inicia - osiris_table_acl_users"
#$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_acl_users.sql
#echo $(date) + "Fin - osiris_table_acl_users" 
echo $(date) + "Inicia - osiris_table_iof_resource"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_resource.sql
echo $(date) + "Fin - osiris_table_iof_resource" 
echo $(date) + "Inicia - osiris_table_iof_users"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_users.sql
echo $(date) + "Fin - osiris_table_iof_users"
echo $(date) + "Inicia - osiris_table_iof_role"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_role.sql
echo $(date) + "Fin - osiris_table_iof_role" 
echo $(date) + "Inicia - osiris_table_iof_permission"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_permission.sql
echo $(date) + "Fin - osiris_table_iof_permission" 
echo $(date) + "Inicia - osiris_table_iof_user_role"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_user_role.sql
echo $(date) + "Fin - osiris_table_iof_user_role" 
echo $(date) + "Inicia - osiris_table_iof_role_permission"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_iof_role_permission.sql
echo $(date) + "Fin - osiris_table_iof_role_permission"
echo $(date) + "Inicia - osiris_table_update_actions"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_update_actions.sql
echo $(date) + "Fin - osiris_table_update_actions" 
echo $(date) + "Inicia - osiris_table_company_contact"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_company_contact.sql
echo $(date) + "Fin - osiris_table_company_contact" 
echo $(date) + "Inicia - osiris_table_projects"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_projects.sql
echo $(date) + "Fin - osiris_table_projects"
echo $(date) + "Inicia - osiris_table_users_projects"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_users_projects.sql
echo $(date) + "Fin - osiris_table_users_projects" 
echo $(date) + "Inicia - osiris_table_activities"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_activities.sql
echo $(date) + "Fin - osiris_table_activities"
echo $(date) + "Inicia -osiris_table_activityDates"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_activityDates.sql
echo $(date) + "Fin - osiris_table_activityDates" 
echo $(date) + "Inicia - osiris_table_addresses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_addresses.sql
echo $(date) + "Fin - osiris_table_addresses" 
echo $(date) + "Inicia - osiris_table_articles_of_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_articles_of_inventories.sql
echo $(date) + "Fin - osiris_table_articles_of_inventories" 
echo $(date) + "Inicia - osiris_table_inventories_photos"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_inventories_photos.sql
echo $(date) + "Fin - osiris_table_inventories_photos" 
echo $(date) + "Inicia - osiris_table_bank_current_balance"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_bank_current_balance.sql
echo $(date) + "Fin - osiris_table_bank_current_balance" 
echo $(date) + "Inicia - osiris_table_bank"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_bank.sql
echo $(date) + "Fin - osiris_table_bank" 
echo $(date) + "Inicia - osiris_table_banks"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_banks.sql
echo $(date) + "Fin - osiris_table_banks" 
echo $(date) + "Inicia - osiris_table_banks_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_banks_tx.sql
echo $(date) + "Fin - osiris_table_banks_tx" 
echo $(date) + "Inicia - 1osiris_table_books"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_books.sql
echo $(date) + "Fin - osiris_table_books" 
echo $(date) + "Inicia - osiris_table_cash_current_balance"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash_current_balance.sql
echo $(date) + "Fin - osiris_table_cash_current_balance" 
echo $(date) + "Inicia - osiris_table_cash"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash.sql
echo $(date) + "Fin - osiris_table_cash" 
echo $(date) + "Inicia - osiris_table_cash_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cash_tx.sql
echo $(date) + "Fin - osiris_table_cash_tx" 
echo $(date) + "Inicia - osiris_table_clock"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_clock.sql
echo $(date) + "Fin - osiris_table_clock" 
echo $(date) + "Inicia - osiris_table_components"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_components.sql
echo $(date) + "Fin -osiris_table_components" 
echo $(date) + "Inicia -osiris_table_contents"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_contents.sql
echo $(date) + "Fin -osiris_table_contents" 
echo $(date) + "Inicia - osiris_table_contract_types"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_contract_types.sql
echo $(date) + "Fin - osiris_table_contract_types" 
echo $(date) + "Inicia - osiris_table_cron_token"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cron_token.sql
echo $(date) + "Fin - osiris_table_cron_token" 
echo $(date) + "Inicia - osiris_table_curses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_curses.sql
echo $(date) + "Fin - osiris_table_curses" 
echo $(date) + "Inicia - osiris_table_cv_curses_employee"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_curses_employee.sql
echo $(date) + "Fin - osiris_table_cv_curses_employee" 
echo $(date) + "Inicia - osiris_table_cv_curses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_curses.sql
echo $(date) + "Fin - osiris_table_cv_curses" 
echo $(date) + "Inicia - osiris_table_cv_job_experience"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_job_experience.sql
echo $(date) + "Fin - osiris_table_cv_job_experience" 
echo $(date) + "Inicia - osiris_table_cv_jobexperience_tech"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_jobexperience_tech.sql
echo $(date) + "Fin -osiris_table_cv_jobexperience_tech" 
echo $(date) + "Inicia - osiris_table_cv_job_users"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_job_users.sql
echo $(date) + "Fin - osiris_table_cv_job_users" 
echo $(date) + "Inicia - osiris_table_cv_language_name"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_language_name.sql
echo $(date) + "Fin - osiris_table_cv_language_name" 
echo $(date) + "Inicia -osiris_table_cv_language"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_language.sql
echo $(date) + "Fin - osiris_table_cv_language" 
echo $(date) + "Inicia - osiris_table_cv"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv.sql
echo $(date) + "Fin - osiris_table_cv" 
echo $(date) + "Inicia - osiris_table_cv_technologies"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_technologies.sql
echo $(date) + "Fin - osiris_table_cv_technologies" 
echo $(date) + "Inicia - osiris_table_cv_tech"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_cv_tech.sql
echo $(date) + "Fin - osiris_table_cv_tech" 
echo $(date) + "Inicia - osiris_table_data_meeting"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_data_meeting.sql
echo $(date) + "Fin -osiris_table_data_meeting" 
echo $(date) + "Inicia -osiris_table_data"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_data.sql
echo $(date) + "Fin -osiris_table_data" 
echo $(date) + "Inicia - osiris_table_datepaymentsorder"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_datepaymentsorder.sql
echo $(date) + "Fin - osiris_table_datepaymentsorder" 
echo $(date) + "Inicia - osiris_table_dates_of_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_dates_of_payments.sql
echo $(date) + "Fin - osiris_table_dates_of_payments" 
echo $(date) + "Inicia - osiris_table_days"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_days.sql
echo $(date) + "Fin - osiris_table_days" 
echo $(date) + "Inicia - osiris_table_documents_fields_content"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents_fields_content.sql
echo $(date) + "Fin - osiris_table_documents_fields_content" 
echo $(date) + "Inicia - osiris_table_documents_fields"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents_fields.sql
echo $(date) + "Fin - osiris_table_documents_fields" 
echo $(date) + "Inicia - osiris_table_documents"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_documents.sql
echo $(date) + "Fin - osiris_table_documents" 
echo $(date) + "Inicia - osiris_table_entry_out_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_entry_out_merchandize.sql
echo $(date) + "Fin - osiris_table_entry_out_merchandize" 
echo $(date) + "Inicia - osiris_table_entry_out_product"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_entry_out_product.sql
echo $(date) + "Fin - osiris_table_entry_out_product" 
echo $(date) + "Inicia - osiris_table_expenses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_expenses.sql
echo $(date) + "Fin - osiris_table_expenses" 
echo $(date) + "Inicia - osiris_table_ho_mondays"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_ho_mondays.sql
echo $(date) + "Fin - osiris_table_ho_mondays" 
echo $(date) + "Inicia - osiris_table_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_inventories.sql
echo $(date) + "Fin - osiris_table_inventories" 
echo $(date) + "Inicia - osiris_table_invoices"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_invoices.sql
echo $(date) + "Fin - osiris_table_invoices" 
echo $(date) + "Inicia - osiris_table_keys"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_keys.sql
echo $(date) + "Fin - osiris_table_keys" 
echo $(date) + "Inicia - osiris_table_liabilities"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_liabilities.sql
echo $(date) + "Fin - osiris_table_liabilities" 
echo $(date) + "Inicia - osiris_table_link"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_link.sql
echo $(date) + "Fin - osiris_table_link" 
echo $(date) + "Inicia - osiris_table_made_payments_order"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_made_payments_order.sql
echo $(date) + "Fin - osiris_table_made_payments_order" 
echo $(date) + "Inicia - osiris_table_made_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_made_payments.sql
echo $(date) + "Fin - osiris_table_made_payments" 
echo $(date) + "Inicia - osiris_table_measuring_unit"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_measuring_unit.sql
echo $(date) + "Fin - Creada osiris_table_measuring_unit" 
echo $(date) + "Inicia - Insertando osiris_table_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_merchandize.sql
echo $(date) + "Fin - osiris_table_merchandize" 
echo $(date) + "Inicia - osiris_table_minutesbyuser"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_minutesbyuser.sql
echo $(date) + "Fin - osiris_table_minutesbyuser" 
echo $(date) + "Inicia - osiris_table_minutes"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_minutes.sql
echo $(date) + "Fin - osiris_table_minutes" 
echo $(date) + "Inicia - osiris_table_num"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_num.sql
echo $(date) + "Fin - osiris_table_num" 
echo $(date) + "Inicia - osiris_table_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_payments.sql
echo $(date) + "Fin - osiris_table_payments" 
echo $(date) + "Inicia - osiris_table_pay_payroll"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_pay_payroll.sql
echo $(date) + "Fin - osiris_table_pay_payroll" 
echo $(date) + "Inicia - osiris_table_preferences"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_preferences.sql
echo $(date) + "Fin - osiris_table_preferences" 
echo $(date) + "Inicia - osiris_table_price_products"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_price_products.sql
echo $(date) + "Fin - osiris_table_price_products" 
echo $(date) + "Inicia - osiris_table_price_services"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_price_services.sql
echo $(date) + "Fin - osiris_table_price_services" 
echo $(date) + "Inicia - osiris_table_products"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_products.sql
echo $(date) + "Fin - osiris_table_products" 
echo $(date) + "Inicia - osiris_table_project_contact"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_project_contact.sql
echo $(date) + "Fin - osiris_table_project_contact" 
echo $(date) + "Inicia - osiris_table_project_role"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_project_role.sql
echo $(date) + "Fin - osiris_table_project_role"  
echo $(date) + "Inicia - osiris_table_purchase2"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase2.sql
echo $(date) + "Fin - osiris_table_purchase2" 
echo $(date) + "Inicia - osiris_table_purchase_invoices"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase_invoices.sql
echo $(date) + "Fin - osiris_table_purchase_invoices" 
echo $(date) + "Inicia - osiris_table_purchase_order"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase_order.sql
echo $(date) + "Fin - osiris_table_purchase_order" 
echo $(date) + "Inicia - osiris_table_purchase"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_purchase.sql
echo $(date) + "Fin - osiris_table_purchase" 
echo $(date) + "Inicia - osiris_table_saleproducts"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_saleproducts.sql
echo $(date) + "Fin - osiris_table_saleproducts" 
echo $(date) + "Inicia - osiris_table_sales"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_sales.sql
echo $(date) + "Fin - osiris_table_sales" 
echo $(date) + "Inicia - osiris_table_services"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_services.sql
echo $(date) + "Fin - osiris_table_services" 
echo $(date) + "Inicia - osiris_table_shoppingcart"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_shoppingcart.sql
echo $(date) + "Fin - osiris_table_shoppingcart" 
echo $(date) + "Inicia - osiris_table_state_of_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_state_of_inventories.sql
echo $(date) + "Fin - osiris_table_state_of_inventories" 
echo $(date) + "Inicia - osiris_table_stock_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_stock_merchandize.sql
echo $(date) + "Fin - osiris_table_stock_merchandize" 
echo $(date) + "Inicia - osiris_table_stock"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_stock.sql
echo $(date) + "Fin - osiris_table_stock" 
echo $(date) + "Inicia - osiris_table_technologies"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_technologies.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_technologies" 
echo $(date) + "Inicia - 1.1_ Insertando osiris_table_techtype"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_techtype.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_techtype" 
echo $(date) + "Inicia - 1.1_ Insertando osiris_table_themes"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_themes.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_themes" 
echo $(date) + "Inicia - 1.1_ Insertando osiris_table_tipo_books"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tipo_books.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_tipo_books" 
echo $(date) + "Inicia - 1.1_ Insertando osiris_table_tipo_tutorial"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tipo_tutorial.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_tipo_tutorial" 
echo $(date) + "Inicia - 1.1_ Insertando osiris_table_topic"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_topic.sql
echo $(date) + "Fin - 1.1_ Creada osiris_table_topic" 
echo $(date) + "Inicia - osiris_table_tutorial"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_tutorial.sql
echo $(date) + "Fin - osiris_table_tutorial" 
echo $(date) + "Inicia - osiris_table_types"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_types.sql
echo $(date) + "Fin - osiris_table_types" 
echo $(date) + "Inicia - osiris_table_user_details"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_user_details.sql
echo $(date) + "Fin - osiris_table_user_details" 
echo $(date) + "Inicia - osiris_table_users_addresses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_users_addresses.sql
echo $(date) + "Fin - osiris_table_users_addresses" 
echo $(date) + "Inicia - osiris_table_vision"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/tables/osiris_table_vision.sql
echo $(date) + "Fin - osiris_table_vision" 
echo Recorded at $DAT $(date) Creando entidades >> ./logs/$LOGFILE 
echo $(date) + "Fin - 1_ Creando entidades" 	

echo $(date) + "Inicia -1.1 Insertando de datos basicos 1B_enlasa"
echo Espere...
echo $(date) + "Inicia - osiris_data_states_of_mexico"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_states_of_mexico.sql
echo $(date) + "Fin - osiris_data_states_of_mexico"
echo $(date) + "Inicia - osiris_data_district"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_district.sql
echo $(date) + "Fin - osiris_data_district"
echo $(date) + "Inicia - osiris_data_neighborhood"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_neighborhood.sql
echo $(date) + "Fin - osiris_data_neighborhood"
echo $(date) + "Inicia - osiris_data_company"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_company.sql
echo $(date) + "Fin - osiris_data_company"
echo $(date) + "Inicia - osiris_data_acc_receivable"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_acc_receivable.sql
echo $(date) + "Fin - osiris_data_acc_receivable"
echo $(date) + "Inicia - osiris_data_category"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_category.sql
echo $(date) + "Fin - osiris_data_category" 
echo $(date) + "Inicia - osiris_data_account"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account.sql
echo $(date) + "Fin - osiris_data_account"
echo $(date) + "Inicia - osiris_data_account_category"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_category.sql
echo $(date) + "Fin - osiris_data_account_category"
echo $(date) + "Inicia - osiris_data_account_current_balance"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_current_balance.sql
echo $(date) + "Fin - osiris_data_account_current_balance"
echo $(date) + "Inicia - osiris_data_account_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_account_tx.sql
echo $(date) + "Fin - osiris_data_account_tx"
echo $(date) + "Inicia - osiris_data_department"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_department.sql
echo $(date) + "Fin - osiris_data_department"
echo $(date) + "Inicia - osiris_data_job_users"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_job_users.sql
echo $(date) + "Fin - osiris_data_job_users"
echo $(date) + "Inicia - osiris_data_days"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_days.sql
echo $(date) + "Fin - osiris_data_days"
echo $(date) + "Inicia - osiris_data_keys"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_keys.sql
echo $(date) + "Fin - osiris_data_keys"
echo $(date) + "Inicia - osiris_data_num"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_num.sql
echo $(date) + "Fin - osiris_data_num"
echo $(date) + "Inicia - osiris_data_preferences"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_preferences.sql
echo $(date) + "Fin - osiris_data_preferences"
echo $(date) + "Inicia - osiris_data_state_of_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_state_of_inventories.sql
echo $(date) + "Fin - osiris_data_state_of_inventories"
echo $(date) + "Inicia - osiris_data_update_actions"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_update_actions.sql
echo $(date) + "Fin - osiris_data_update_actions"
echo $(date) + "Fin -1.1_ 	Inicia insercion de datos basicos 1B_enlasa"

echo $(date) + "Inicia - 2_ Insertando datos en entidades ACL Osiris"
echo Espere...
echo $(date) + "Inicia - 1_B_osiris_data_iof_role_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/1_B_osiris_data_iof_role_enlasa.sql
echo $(date) + "Fin - 1_B_osiris_data_iof_role_enlasa" 
echo $(date) + "Inicia - 2_B_osiris_data_iof_users_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/2_B_osiris_data_iof_users_enlasa.sql
echo $(date) + "Fin - 2_B_osiris_data_iof_users_enlasa" 
echo $(date) + "Inicia - 3_B_osiris_data_iof_resource_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/3_B_osiris_data_iof_resource_enlasa.sql
echo $(date) + "Fin - 3_B_osiris_data_iof_resource_enlasa" 
echo $(date) + "Inicia - 4_B_osiris_data_iof_permission_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/4_B_osiris_data_iof_permission_enlasa.sql
echo $(date) + "Fin - 4_B_osiris_data_iof_permission_enlasa" 
echo $(date) + "Inicia - 5_B_osiris_data_iof_user_role_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/5_B_osiris_data_iof_user_role_enlasa.sql
echo $(date) + "Fin - 5_B_osiris_data_iof_user_role_enlasa" 
echo $(date) + "Inicia - 6_B_osiris_data_iof_role_permission_enlasa"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./1B_enlasa/data/6_B_osiris_data_iof_role_permission_enlasa.sql
echo $(date) + "Fin - 6_B_osiris_data_iof_role_permission_enlasa"
echo Recorded at $DAT $(date) Insertando datos en entidades ACL Osiris >> ./logs/$LOGFILE
echo $(date) + "Fin - 2_ Insertando datos en entidades ACL Osiris"

echo $(date) + "Inicia - 3_ Insertando datos en entidades"
echo Espere...
echo $(date) + "Inicia - osiris_data_activities"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_activities.sql
echo $(date) + "Fin - osiris_data_activities" 
echo $(date) + "Inicia - osiris_data_activityDates"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_activityDates.sql
echo $(date) + "Fin - osiris_data_activityDates" 
echo $(date) + "Inicia - osiris_data_articles_of_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_articles_of_inventories.sql
echo $(date) + "Fin - osiris_data_articles_of_inventories" 
echo $(date) + "Inicia - osiris_data_bank_current_balance"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_bank_current_balance.sql
echo $(date) + "Fin - osiris_data_bank_current_balance" 
echo $(date) + "Inicia - osiris_data_bank"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_bank.sql
echo $(date) + "Fin - osiris_data_bank" 
echo $(date) + "Inicia - osiris_data_banks"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_banks.sql
echo $(date) + "Fin - osiris_data_banks" 
echo $(date) + "Inicia - osiris_data_banks_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_banks_tx.sql
echo $(date) + "Fin - osiris_data_banks_tx" 
echo $(date) + "Inicia - osiris_data_books"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_books.sql
echo $(date) + "Fin - osiris_data_books" 
echo $(date) + "Inicia - osiris_data_cash_current_balance"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash_current_balance.sql
echo $(date) + "Fin - osiris_data_cash_current_balance" 
echo $(date) + "Inicia - osiris_data_cash"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash.sql
echo $(date) + "Fin - osiris_data_cash" 
echo $(date) + "Inicia - osiris_data_cash_tx"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cash_tx.sql
echo $(date) + "Fin - osiris_data_cash_tx" 
echo $(date) + "Inicia - osiris_data_clock"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_clock.sql
echo $(date) + "Fin - osiris_data_clock" 
echo $(date) + "Inicia - osiris_data_company_contact"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_company_contact.sql
echo $(date) + "Fin -osiris_data_company_contact" 
echo $(date) + "Inicia  osiris_data_components"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_components.sql
echo $(date) + "Fin - osiris_data_components" 
echo $(date) + "Inicia - osiris_data_contents"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_contents.sql
echo $(date) + "Fin - osiris_data_contents" 
echo $(date) + "Inicia - osiris_data_contract_types"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_contract_types.sql
echo $(date) + "Fin - osiris_data_contract_types" 
echo $(date) + "Inicia - osiris_data_cron_token"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cron_token.sql
echo $(date) + "Fin - osiris_data_cron_token" 
echo $(date) + "Inicia - osiris_data_curses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_curses.sql
echo $(date) + "Fin - osiris_data_curses" 
echo $(date) + "Inicia - osiris_data_cv_curses_employee"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_curses_employee.sql
echo $(date) + "Fin - osiris_data_cv_curses_employee" 
echo $(date) + "Inicia - osiris_data_cv_curses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_curses.sql
echo $(date) + "Fin - osiris_data_cv_curses" 
echo $(date) + "Inicia - osiris_data_cv_job_experience"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_job_experience.sql
echo $(date) + "Fin - osiris_data_cv_job_experience" 
echo $(date) + "Inicia - osiris_data_cv_jobexperience_tech"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_jobexperience_tech.sql
echo $(date) + "Fin - osiris_data_cv_jobexperience_tech" 
echo $(date) + "Inicia - osiris_data_cv_job_users"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_job_users.sql
echo $(date) + "Fin - osiris_data_cv_job_users" 
echo $(date) + "Inicia - osiris_data_cv_language_name"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_language_name.sql
echo $(date) + "Fin - osiris_data_cv_language_name" 
echo $(date) + "Inicia - osiris_data_cv_language"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_language.sql
echo $(date) + "Fin - osiris_data_cv_language" 
echo $(date) + "Inicia - osiris_data_cv"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv.sql
echo $(date) + "Fin -osiris_data_cv" 
echo $(date) + "Inicia - osiris_data_cv_technologies"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_technologies.sql
echo $(date) + "Fin - osiris_data_cv_technologies" 
echo $(date) + "Inicia - osiris_data_cv_tech"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_cv_tech.sql
echo $(date) + "Fin - osiris_data_cv_tech" 
echo $(date) + "Inicia - osiris_data_data_meeting"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_data_meeting.sql
echo $(date) + "Fin - osiris_data_data_meeting" 
echo $(date) + "Inicia - osiris_data_data"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_data.sql
echo $(date) + "Fin - osiris_data_data" 
echo $(date) + "Inicia - osiris_data_datepaymentsorder"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_datepaymentsorder.sql
echo $(date) + "Fin - osiris_data_datepaymentsorder" 
echo $(date) + "Inicia - osiris_data_dates_of_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_dates_of_payments.sql
echo $(date) + "Fin - osiris_data_dates_of_payments"    
echo $(date) + "Inicia - osiris_data_documents_fields_content"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents_fields_content.sql
echo $(date) + "Fin - osiris_data_documents_fields_content" 
echo $(date) + "Inicia - osiris_data_documents_fields"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents_fields.sql
echo $(date) + "Fin - osiris_data_documents_fields" 
echo $(date) + "Inicia - osiris_data_documents"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_documents.sql
echo $(date) + "Fin - osiris_data_documents" 
echo $(date) + "Inicia - osiris_data_entry_out_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_entry_out_merchandize.sql
echo $(date) + "Fin - osiris_data_entry_out_merchandize" 
echo $(date) + "Inicia - osiris_data_entry_out_product"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_entry_out_product.sql
echo $(date) + "Fin - osiris_data_entry_out_product" 
echo $(date) + "Inicia - osiris_data_expenses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_expenses.sql
echo $(date) + "Fin - osiris_data_expenses" 
echo $(date) + "Inicia - osiris_data_ho_mondays"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_ho_mondays.sql
echo $(date) + "Fin - osiris_data_ho_mondays" 
echo $(date) + "Inicia - osiris_data_inventories"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_inventories.sql
echo $(date) + "Fin - osiris_data_inventories" 
echo $(date) + "Inicia - osiris_data_invoices"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_invoices.sql
echo $(date) + "Fin - osiris_data_invoices" 
echo $(date) + "Inicia - osiris_data_liabilities"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_liabilities.sql
echo $(date) + "Fin - osiris_data_liabilities" 
echo $(date) + "Inicia - osiris_data_link"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_link.sql
echo $(date) + "Fin - osiris_data_link" 
echo $(date) + "Inicia - osiris_data_made_payments_order"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_made_payments_order.sql
echo $(date) + "Fin - osiris_data_made_payments_order" 
echo $(date) + "Inicia - osiris_data_made_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_made_payments.sql
echo $(date) + "Fin - osiris_data_made_payments" 
echo $(date) + "Inicia - osiris_data_measuring_unit"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_measuring_unit.sql
echo $(date) + "Fin - osiris_data_measuring_unit" 
echo $(date) + "Inicia - osiris_data_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_merchandize.sql
echo $(date) + "Fin - osiris_data_merchandize" 
echo $(date) + "Inicia - osiris_data_minutesbyuser"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_minutesbyuser.sql
echo $(date) + "Fin - osiris_data_minutesbyuser" 
echo $(date) + "Inicia - osiris_data_minutes"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_minutes.sql
echo $(date) + "Fin - osiris_data_minutes" 
echo $(date) + "Inicia - osiris_data_addresses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_addresses.sql
echo $(date) + "Fin - osiris_data_addresses" 
echo $(date) + "Inicia - osiris_data_payments"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_payments.sql
echo $(date) + "Fin - osiris_data_payments" 
echo $(date) + "Inicia - osiris_data_pay_payroll"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_pay_payroll.sql
echo $(date) + "Fin - osiris_data_pay_payroll" 
echo $(date) + "Inicia - osiris_data_price_products"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_price_products.sql
echo $(date) + "Fin - osiris_data_price_products" 
echo $(date) + "Inicia - osiris_data_price_services"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_price_services.sql
echo $(date) + "Fin - osiris_data_price_services" 
echo $(date) + "Inicia - osiris_data_products"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_products.sql
echo $(date) + "Fin - osiris_data_products" 
echo $(date) + "Inicia - osiris_data_project_contact"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_project_contact.sql
echo $(date) + "Fin - osiris_data_project_contact" 
echo $(date) + "Inicia - osiris_data_project_role"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_project_role.sql
echo $(date) + "Fin - osiris_data_project_role" 
echo $(date) + "Inicia - osiris_data_projects"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_projects.sql
echo $(date) + "Fin - osiris_data_projects" 
echo $(date) + "Inicia - osiris_data_purchase2"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase2.sql
echo $(date) + "Fin - osiris_data_purchase2" 
echo $(date) + "Inicia - osiris_data_purchase_invoices"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase_invoices.sql
echo $(date) + "Fin -osiris_data_purchase_invoices" 
echo $(date) + "Inicia - osiris_data_purchase_order"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase_order.sql
echo $(date) + "Fin - osiris_data_purchase_order" 
echo $(date) + "Inicia - osiris_data_purchase"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_purchase.sql
echo $(date) + "Fin - osiris_data_purchase" 
echo $(date) + "Inicia - osiris_data_saleproducts"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_saleproducts.sql
echo $(date) + "Fin - osiris_data_saleproducts" 
echo $(date) + "Inicia - osiris_data_sales"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_sales.sql
echo $(date) + "Fin - osiris_data_sales" 
echo $(date) + "Inicia - osiris_data_services"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_services.sql
echo $(date) + "Fin - osiris_data_services" 
echo $(date) + "Inicia - osiris_data_shoppingcart"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_shoppingcart.sql
echo $(date) + "Fin - osiris_data_shoppingcart" 
echo $(date) + "Inicia - osiris_data_stock_merchandize"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_stock_merchandize.sql
echo $(date) + "Fin - osiris_data_stock_merchandize" 
echo $(date) + "Inicia - osiris_data_stock"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_stock.sql
echo $(date) + "Fin - osiris_data_stock" 
echo $(date) + "Inicia - osiris_data_technologies"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_technologies.sql
echo $(date) + "Fin - osiris_data_technologies" 
echo $(date) + "Inicia - osiris_data_techtype"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_techtype.sql
echo $(date) + "Fin - osiris_data_techtype" 
echo $(date) + "Inicia - osiris_data_themes"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_themes.sql
echo $(date) + "Fin - osiris_data_themes" 
echo $(date) + "Inicia - osiris_data_tipo_books"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tipo_books.sql
echo $(date) + "Fin - osiris_data_tipo_books" 
echo $(date) + "Inicia - osiris_data_tipo_tutorial"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tipo_tutorial.sql
echo $(date) + "Fin - osiris_data_tipo_tutorial" 
echo $(date) + "Inicia - osiris_data_topic"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_topic.sql
echo $(date) + "Fin - osiris_data_topic" 
echo $(date) + "Inicia - osiris_data_tutorial"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_tutorial.sql
echo $(date) + "Fin - osiris_data_tutorial" 
echo $(date) + "Inicia - osiris_data_types"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_types.sql
echo $(date) + "Fin - osiris_data_types" 
echo $(date) + "Inicia - osiris_data_user_details"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_user_details.sql
echo $(date) + "Fin - osiris_data_user_details" 
echo $(date) + "Inicia - osiris_data_users_addresses"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_users_addresses.sql
echo $(date) + "Fin - osiris_data_users_addresses" 
echo $(date) + "Inicia - osiris_data_users_projects"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_users_projects.sql
echo $(date) + "Fin - osiris_data_users_projects" 
echo $(date) + "Inicia - osiris_data_vision"
$OSIRISPATH -u root -proot --default-character-set=utf8 osiris < ./0_osiris/data/osiris_data_vision.sql
echo $(date) + "Fin - osiris_data_vision" 
echo Recorded at $DAT $(date) Insertando datos en entidades >> ./logs/$LOGFILE
echo $(date) + "Fin - 3_ Insertando datos en entidades"