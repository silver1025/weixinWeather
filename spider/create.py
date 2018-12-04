# -*- coding: UTF-8 -*-
import MySQLdb

if __name__ == "__main__":
    # 打开数据库连接
    db = MySQLdb.connect("127.0.0.1", "sql123_207_170_", "cGFERkG77D", "sql123_207_170_", charset='utf8')
    # 使用cursor()方法获取操作游标
    cursor = db.cursor()
    # 如果数据表不存在，创建数据表SQL语句
    sql = """CREATE TABLE IF NOT EXISTS weixinWeather (
             cityname  CHAR(20) NOT NULL,
             citycode  CHAR(20) NOT NULL,
             today VARCHAR(500),  
             day1 VARCHAR(500), 
             day2 VARCHAR(500), 
             day3 VARCHAR(500),
             day4 VARCHAR(500))"""
    cursor.execute(sql)
    sql = "SELECT county_name, weather_code FROM ins_county"
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        cityname = row[0]
        citycode = row[1]
        sql = """
        INSERT INTO weixinWeather(cityname, citycode) VALUES ("%s", "%s")
        """ % (cityname, citycode)
        try:
           # 执行sql语句
           cursor.execute(sql)
           # 提交到数据库执行
           db.commit()
           print(citycode)
        except:
           # 发生错误时回滚
           db.rollback()