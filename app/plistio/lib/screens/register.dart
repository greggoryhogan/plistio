import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:dio/dio.dart';
import 'dashboard.dart';
import 'package:plistio/screens/login.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:crypto/crypto.dart';
import 'dart:convert'; // for the utf8.encode method
import 'package:shared_preferences/shared_preferences.dart';

class Register extends StatefulWidget {
  @override
  _RegisterState createState() => _RegisterState();
}

class _RegisterState extends State<Register> {
  TextEditingController user = TextEditingController();
  TextEditingController pass = TextEditingController();

  Future register() async {
    final _prefs = await SharedPreferences.getInstance();
    try {
      var bytes = utf8.encode(pass.text);
      FormData formData = new FormData.fromMap({
        'email': user.text,
        'password': md5.convert(bytes),
        'passphrase': dotenv.env['PLISTIO_PASSPHRASE']
      });

      var response = await Dio()
          .post('https://plistio-auth.local/user/add-new.php', data: formData);

      var data = response.data;
      if (response.statusCode == 200) {
        _prefs.setString('auth_code', data['auth_code']);
        FlutterToast(context).showToast(
            child: Text(data['message'],
                style: TextStyle(fontSize: 25, color: Colors.green)));
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DashBoard(),
          ),
        );
      }
    } on DioError catch (e) {
      if (e.response.statusCode == 400) {
        FlutterToast(context).showToast(
            child: Text(
          e.response.data['message'],
          style: TextStyle(fontSize: 25, color: Colors.red),
        ));
      } else {
        print(e.message);
        //print(e.request);
      }
    } /*catch (e) {
        print(e);
        /*FlutterToast(context).showToast(
            child: Text(e.response,
                style: TextStyle(fontSize: 25, color: Colors.green)));*/
        /*Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => DashBoard(),
          ),
        );*/
      }*/
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Plistio',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
      ),
      body: Container(
        child: Card(
          color: Colors.amber,
          child: Column(
            children: <Widget>[
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(
                  'Register',
                  style: TextStyle(fontSize: 25, fontWeight: FontWeight.bold),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextField(
                  decoration: InputDecoration(
                    labelText: 'Username',
                    prefixIcon: Icon(Icons.person),
                    border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8)),
                  ),
                  controller: user,
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: TextField(
                  obscureText: true,
                  decoration: InputDecoration(
                    labelText: 'Password',
                    prefixIcon: Icon(Icons.lock),
                    border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(8)),
                  ),
                  controller: pass,
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: MaterialButton(
                  color: Colors.pink,
                  child: Text('Register',
                      style: TextStyle(
                          fontSize: 30,
                          fontWeight: FontWeight.bold,
                          color: Colors.white)),
                  onPressed: () {
                    register();
                  },
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => Login(),
                      ),
                    );
                  },
                  child: new Text("Already have an account? Log In"),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
