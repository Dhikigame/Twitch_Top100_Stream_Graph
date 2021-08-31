import os
import sys
import MySQLdb
import platform
import json
sys.path.append("../info")
from info.information import Information


class DB_operation:

    def mysql_connection():
        
        os_system = platform.system()
        if "Darwin" in os_system:
            con = MySQLdb.connect(unix_socket=Information.db_socket("Darwin"),user=Information.db_user("Darwin"),passwd=Information.db_passwd("Darwin"),host=Information.db_host("Darwin"),db=Information.db_name("Darwin"),use_unicode=Information.use_unicode("Darwin"),charset=Information.charset("Darwin"))
        else:
            con = MySQLdb.connect(unix_socket=Information.db_socket("Linux"),user=Information.db_user("Linux"),passwd=Information.db_passwd("Linux"),host=Information.db_host("Linux"),db=Information.db_name("Linux"),use_unicode=Information.use_unicode("Linux"),charset=Information.charset("Linux"))
        cursor = con.cursor()

        return cursor

    def select_data_streamdata_list(cursor, search_user_id, condition_first_date, condition_end_date):

        sql = "select viewer_count, currentdate from stream_data where user_id = " + str(search_user_id) + " && ('" + str(condition_first_date) + "' <= currentdate && currentdate <= '" + str(condition_end_date) + "') order by currentdate asc"

        cursor.execute(sql)

        streamdata_list = cursor.fetchall()

        return streamdata_list

    def mysql_close(cursor, con=None):

        # con.commit()
        cursor.close()