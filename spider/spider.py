# coding:utf-8
import MySQLdb
import requests
import json
import time

if __name__ == "__main__":
    while True:
        # 打开数据库连接
        db = MySQLdb.connect("127.0.0.1", "sql123_207_170_", "cGFERkG77D", "sql123_207_170_", charset='utf8')
        # 使用cursor()方法获取操作游标
        cursor = db.cursor()
        prefix = 'http://t.weather.sojson.com/api/weather/city/'
        sql = "SELECT citycode FROM weixin_weather"
        cursor.execute(sql)
        results = cursor.fetchall()
        num = 1.0
        sum = 2564
        for row in results:
            url = prefix + row[0]
            req = requests.get(url).json()
            if req.get("status", 0) == 200:
                # if not req.has_key("time"):
                #     print(json.dumps(req, encoding="UTF-8", ensure_ascii=False))
                #     continue
                # else:
                updatetime = req.get("time", 0)
                if req["data"].has_key("pm25"):
                    pm25 = req["data"]["pm25"]
                    quality = json.dumps(req["data"]["quality"], encoding="UTF-8", ensure_ascii=False)
                else:
                    pm25 = "N/A"
                    quality = "N/A"
                data = req["data"]["forecast"]
                sql = """UPDATE weixin_weather SET updatetime= '%s',shidu= '%s',pm25= '%s',quality= '%s',wendu= '%s',day0 = '%s', day1 = '%s', day2 = '%s', day3 = '%s', day4 = '%s' WHERE citycode = '%s' """ \
                      % (updatetime,
                         req["data"]["shidu"],
                         pm25,
                         quality,
                         req["data"]["wendu"],
                         json.dumps(data[0], encoding="UTF-8", ensure_ascii=False),
                         json.dumps(data[1], encoding="UTF-8", ensure_ascii=False),
                         json.dumps(data[2], encoding="UTF-8", ensure_ascii=False),
                         json.dumps(data[3], encoding="UTF-8", ensure_ascii=False),
                         json.dumps(data[4], encoding="UTF-8", ensure_ascii=False),
                         row[0])
                try:
                    # 执行sql语句
                    cursor.execute(sql)
                    # 提交到数据库执行
                    db.commit()
                    # 百分号不能放在前面，有未知bug
                    print("更新进度：%.3f" % (100 * num / sum) + "%")
                except:
                    # 发生错误时回滚
                    print("SQL failure!")
                    db.rollback()
                time.sleep(1)
            else:
                print(row[0])
                # 连在一起会报编码错误
                print("更新失败！")
            num += 1
        # 每8小时更新一次
        time.sleep(8 * 60 * 60)
