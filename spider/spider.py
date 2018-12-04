#coding:utf-8
import MySQLdb
import requests
import json
import time

if __name__ == "__main__":
    # 打开数据库连接
    db = MySQLdb.connect("127.0.0.1", "sql123_207_170_", "cGFERkG77D", "sql123_207_170_", charset='utf8')
    # 使用cursor()方法获取操作游标
    cursor = db.cursor()
    prefix = 'http://t.weather.sojson.com/api/weather/city/'
    sql = "SELECT citycode FROM weixinWeather"
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        url = prefix + row[0]
        req = requests.get(url)
        req = req.text.encode("utf-8")
        print(req)
        break
