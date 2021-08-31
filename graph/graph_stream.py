import matplotlib.pyplot as plt


class Graph_Stream:

    def __init__(self, viewercount_list, currentdate_list):
        self.y_viewercount_list = viewercount_list
        self.x_currentdate_list = currentdate_list

    def create(self):
        plt.grid(True)
        plt.plot(self.x_currentdate_list, self.y_viewercount_list)
        plt.savefig("graph.png")
        plt.show()