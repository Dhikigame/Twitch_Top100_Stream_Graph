import statistics


class Stats_Stream:

    def __init__(self, viewercount_list, currentdate_list):
        self.viewercount_list = viewercount_list
        self.currentdate_list = currentdate_list

    def stats(self):
        print("平均値")
        print(statistics.mean(self.viewercount_list))
        print("中央値")
        print(statistics.median(self.viewercount_list))
        print("最高値")
        print(max(self.viewercount_list))
        viewercount_list_index = self.viewercount_list.index(max(self.viewercount_list))
        print(self.currentdate_list[viewercount_list_index])
        print("最小値")
        print(min(self.viewercount_list))
        viewercount_list_index = self.viewercount_list.index(min(self.viewercount_list))
        print(self.currentdate_list[viewercount_list_index])