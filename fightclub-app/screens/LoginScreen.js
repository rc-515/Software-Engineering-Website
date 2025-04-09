import React, { useState } from "react";
import { View, TextInput, Button, Alert } from "react-native";
import axios from "axios";

export default function LoginScreen({ navigation }) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = async () => {
    try {
      const response = axios.post("http://YOUR_LOCAL_IP/Software-Engineering-Website/api/login.php", {
        email,
        password,
      });
      navigation.navigate("Dashboard", { user: response.data });
    } catch(err) {
        Alert.alert("Login Failed", err.response?.data?.message || "Unknown Error");
    }
  };

  return (
    <View style = {{padding: 20}}>
      <Text> Email: </Text>
      <TextInput value={password} onChangeText={setPassword} autoCapitalize="none" />
      <Button title="Login" onPress={handleLogin} />
      <Text> Password: </Text>
      <Button title="Login" onPress={login} />
      <Text style={{ marginTop: 20 }} onPress= {() => navigation.navigate('Register')} />
    </View>
  );
}
