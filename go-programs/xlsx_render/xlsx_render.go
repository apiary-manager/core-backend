package main

import (
	xlst "./package"
	"bufio"
	"encoding/json"
	"fmt"
	"os"
)

func main() {
	template := os.Args[1]
	savePath := os.Args[2]
	dataFile := os.Args[3]

	var dat map[string]interface{}

	data := getData(dataFile)
	json.Unmarshal([]byte(data), &dat)

	var doc = xlst.New()

	doc.OpenFileTemplate(template)

	err := doc.Render(dat)
	if err != nil {
		panic(err)
	}

	err = doc.Save(savePath)
	if err != nil {
		panic(err)
	}

	deleteFileData(dataFile)
}

func getData(path string) string {
	f, err2 := os.Open(path)
	if err2 != nil {
		fmt.Printf("error opening data file: %v\n",err2)
		os.Exit(1)
	}
	r := bufio.NewReader(f)
	var s, b, e = r.ReadLine()
	if e == nil && b == false {
		return string(s)
	}
	return ""
}

func deleteFileData(path string) {
    err := os.Remove(path)

    if err != nil {
      fmt.Println(err)
      return
    }
}
