# -*- coding: utf8 -*-

from openpyxl import load_workbook
import json

wb = load_workbook(filename="data.xlsx", read_only=True)

data = dict()
for row in wb['data'].rows:
  city = row[0].value
  num = row[1].value
  shop = row[2].value
  if num is None:
    continue

  if city not in shop:
    if city  not in data:
      data[city] = list()

    try:
      data[city].append(shop)
    except:
      print u"%s  %s 失败" %(city, shop)

json_data = json.dumps(data)

f = open("./data.json", mode="w")
f.write(json_data)
f.close()