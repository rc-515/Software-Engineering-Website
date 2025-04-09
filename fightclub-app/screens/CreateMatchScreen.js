import React, { useState } from 'react';
import { View, TextInput, Button, Text, Alert } from 'react-native';
import { createMatch } from '../services/api';

export default function CreateMatchScreen({ route, navigation }) {
  const challenger = route.params?.email;
  const [opponent, setOpponent] = useState('');
  const [date, setDate] = useState('');

  const create = async () => {
    try {
      await createMatch({
        challenger_name: challenger,
        opponent_name: opponent,
        fight_date: date
      });
      Alert.alert("Success", "Match created");
      navigation.navigate("Dashboard", { user: { email: challenger } });
    } catch (err) {
      Alert.alert("Error", err.response?.data?.error || "Something went wrong");
    }
  };

  return (
    <View style={{ padding: 20 }}>
      <Text>Opponent Email:</Text>
      <TextInput value={opponent} onChangeText={setOpponent} />
      <Text>Date (YYYY-MM-DD):</Text>
      <TextInput value={date} onChangeText={setDate} />
      <Button title="Schedule Match" onPress={create} />
    </View>
  );
}
