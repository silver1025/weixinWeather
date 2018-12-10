# -*- coding: UTF-8 -*-
import MySQLdb

if __name__ == "__main__":
    # 打开数据库连接
    db = MySQLdb.connect("127.0.0.1", "sql123_207_170_", "cGFERkG77D", "sql123_207_170_", charset='utf8')
    # 使用cursor()方法获取操作游标
    cursor = db.cursor()
    # 如果数据表不存在，创建数据表SQL语句
    sql = """CREATE TABLE IF NOT EXISTS weixin_weather (
             ID int NOT NULL AUTO_INCREMENT, 
             cityname  CHAR(20) NOT NULL,
             citycode  CHAR(20) NOT NULL,
             updatetime CHAR(30),
			 shidu CHAR(10),
			 pm25 CHAR(10),
			 quality CHAR(10),
			 wendu CHAR(10),
             day0 VARCHAR(500),  
             day1 VARCHAR(500), 
             day2 VARCHAR(500), 
             day3 VARCHAR(500),
             day4 VARCHAR(500),
             PRIMARY KEY (ID))"""
    cursor.execute(sql)
    sql = "SELECT county_name, weather_code FROM ins_county"
    cursor.execute(sql)
    results = cursor.fetchall()
    for row in results:
        cityname = row[0]
        citycode = row[1]
        sql = """
        INSERT INTO weixin_weather(cityname, citycode) VALUES ("%s", "%s")
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
