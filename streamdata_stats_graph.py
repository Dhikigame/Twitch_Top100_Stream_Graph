# import db.db_get import db_streamdata_get
from db_streamdata.db_operation import DB_operation
from graph.graph_stream import Graph_Stream
from stats.stats_stream import Stats_Stream


class Stats_Graph_Info:

    def __init__(self, user_id, first_date, end_date):
        self.cursor = DB_operation.mysql_connection()
        self.user_id = user_id
        self.first_date = first_date
        self.end_date = end_date
        self.streamdata_list = []
        self.viewercount_list = []
        self.currentdate_list = []

    def streamdata_get(self):
        self.streamdata_list = DB_operation.select_data_streamdata_list(self.cursor, self.user_id, self.first_date, self.end_date)

    def viewercount_list_append(self):
        for streamdata in self.streamdata_list:
            self.viewercount_list.append(streamdata[0])

    def currentdate_list_append(self):
        for streamdata in self.streamdata_list:
            self.currentdate_list.append(streamdata[1])

    def stats_create(self):
        stats_stream = Stats_Stream(self.viewercount_list, self.currentdate_list)
        stats_stream.stats()

    def graph_create(self):
        graph_stream = Graph_Stream(self.viewercount_list, self.currentdate_list)
        graph_stream.create()

    def db_operation_get(self):
        return self.cursor

user_id = 57292293
first_date = "2021-08-30 04:15:00"
end_date = "2021-08-31 06:09:00"

stats_graph_info = Stats_Graph_Info(user_id, first_date, end_date)
stats_graph_info.streamdata_get()
stats_graph_info.viewercount_list_append()
stats_graph_info.currentdate_list_append()
stats_graph_info.stats_create()
stats_graph_info.graph_create()
DB_operation.mysql_close(stats_graph_info.db_operation_get())