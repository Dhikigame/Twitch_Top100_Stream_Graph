


class Streamdata_Column_List:

    def __init__(self, streamdata_list, rank):
        self.streamdata_all_list = streamdata_list

    def streamdata_column_list(self, rank):
        if rank == 99:
            print(self.streamdata_all_list[1][1])