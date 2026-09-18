<?php

function predict_pcod_risk($features) {
    if ($features['Q4_Not Applicable'] <= 0.5) {
        if ($features['Q14_Yes'] <= 0.5) {
            if ($features['Q25_Not Applicable'] <= 0.5) {
                if ($features['Q1_Yes'] <= 0.5) {
                    if ($features['Q23_No'] <= 0.5) {
                        if ($features['Q3_Yes'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q22_Not tested'] <= 0.5) {
                                    if ($features['Q18_6-8 hours'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q11_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q21_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                        if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q4_1 time'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q9_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q21_No'] <= 0.5) {
                                            if ($features['Q26_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q4_1 time'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q14_Not done'] <= 0.5) {
                                            if ($features['Q26_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q21_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q22_No'] <= 0.5) {
                                                if ($features['Q20_Not sure'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q17_No'] <= 0.5) {
                                        if ($features['Q6_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q22_Not tested'] <= 0.5) {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Q20_Not sure'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            } else {
                                if ($features['Q8_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q26_Yes'] <= 0.5) {
                                        if ($features['Q21_Yes'] <= 0.5) {
                                            if ($features['Q9_No'] <= 0.5) {
                                                if ($features['Q12_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q16_1-2 times/week'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q22_Yes'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                    if ($features['Q20_Yes'] <= 0.5) {
                                        if ($features['Q3_No'] <= 0.5) {
                                            if ($features['Q8_No'] <= 0.5) {
                                                if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 62];
                                                }
                                            } else {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 70];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                if ($features['Q7_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q13_Yes'] <= 0.5) {
                                            if ($features['Q11_Not tested'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 90];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q24_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                    if ($features['Q9_No'] <= 0.5) {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            if ($features['Q4_More than 3 times'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q13_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q7_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q3_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q14_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q13_No'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q10_No'] <= 0.5) {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q7_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q9_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q4_1 time'] <= 0.5) {
                                if ($features['Q18_More than 8 hours'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q17_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q14_Not done'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Q17_Yes'] <= 0.5) {
                                        if ($features['Q18_More than 8 hours'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_Not sure'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q12_No'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q9_Yes'] <= 0.5) {
                        if ($features['Q23_Yes'] <= 0.5) {
                            if ($features['Q12_Yes'] <= 0.5) {
                                if ($features['Q24_No'] <= 0.5) {
                                    if ($features['Q13_Yes'] <= 0.5) {
                                        if ($features['Q26_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q4_2–3 times'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q11_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q14_Not done'] <= 0.5) {
                                            if ($features['Q3_No'] <= 0.5) {
                                                if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q26_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 75];
                                                }
                                            }
                                        } else {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q11_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        return ['risk_level' => 'None', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                    if ($features['Q13_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q11_No'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q4_1 time'] <= 0.5) {
                                        if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q8_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q13_Yes'] <= 0.5) {
                                if ($features['Q7_Yes'] <= 0.5) {
                                    if ($features['Q26_Yes'] <= 0.5) {
                                        if ($features['Q22_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                if ($features['Q5_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q4_2–3 times'] <= 0.5) {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q19_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q16_Rarely'] <= 0.5) {
                                        if ($features['Q18_6-8 hours'] <= 0.5) {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q4_2–3 times'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_No'] <= 0.5) {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q11_No'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q22_No'] <= 0.5) {
                                            if ($features['Q3_Yes'] <= 0.5) {
                                                if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q2_Irregular'] <= 0.5) {
                                                if ($features['Q18_6-8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                        if ($features['Q18_6-8 hours'] <= 0.5) {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q12_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q12_Yes'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q3_No'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q8_Yes'] <= 0.5) {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                if ($features['Q13_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 53];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q23_Yes'] <= 0.5) {
                                        if ($features['Q26_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q17_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 72];
                                                }
                                            } else {
                                                if ($features['Q22_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q8_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q17_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q14_No'] <= 0.5) {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q13_No'] <= 0.5) {
                                    if ($features['Q4_1 time'] <= 0.5) {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            if ($features['Q26_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q19_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q4_2–3 times'] <= 0.5) {
                                        if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q22_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q23_Yes'] <= 0.5) {
                                if ($features['Q3_No'] <= 0.5) {
                                    if ($features['Q5_Yes'] <= 0.5) {
                                        if ($features['Q4_1 time'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q8_No'] <= 0.5) {
                                        if ($features['Q22_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q11_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q17_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q18_6-8 hours'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q10_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($features['Q1_Yes'] <= 0.5) {
                    if ($features['Q3_No'] <= 0.5) {
                        if ($features['Q23_Yes'] <= 0.5) {
                            if ($features['Q9_No'] <= 0.5) {
                                if ($features['Q17_No'] <= 0.5) {
                                    if ($features['Q20_No'] <= 0.5) {
                                        if ($features['Q24_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q5_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q19_No'] <= 0.5) {
                                            if ($features['Q26_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q13_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                if ($features['Q11_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q8_Yes'] <= 0.5) {
                                        if ($features['Q4_More than 3 times'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q12_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q22_Not tested'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 75];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q22_Yes'] <= 0.5) {
                                    if ($features['Q2_Irregular'] <= 0.5) {
                                        if ($features['Q26_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q19_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 75];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            if ($features['Q27_Not sure / Don’t kNow'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q26_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 57];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q20_No'] <= 0.5) {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q14_Not done'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q4_More than 3 times'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q16_Rarely'] <= 0.5) {
                                if ($features['Q4_1 time'] <= 0.5) {
                                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q19_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q17_Yes'] <= 0.5) {
                                        if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                            if ($features['Q26_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q2_Irregular'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q26_Yes'] <= 0.5) {
                                    if ($features['Q18_6-8 hours'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                if ($features['Q12_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q21_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q2_Irregular'] <= 0.5) {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                if ($features['Q9_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q20_Not sure'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q26_Yes'] <= 0.5) {
                            if ($features['Q10_Yes'] <= 0.5) {
                                if ($features['Q9_No'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q23_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q13_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 62];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q2_Irregular'] <= 0.5) {
                                            if ($features['Q20_Yes'] <= 0.5) {
                                                if ($features['Q27_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                if ($features['Q7_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q22_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q23_No'] <= 0.5) {
                                    if ($features['Q22_Not tested'] <= 0.5) {
                                        if ($features['Q7_Yes'] <= 0.5) {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q4_2–3 times'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            if ($features['Q24_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q4_More than 3 times'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            if ($features['Q12_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q8_No'] <= 0.5) {
                                        if ($features['Q11_Not tested'] <= 0.5) {
                                            if ($features['Q17_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q14_Not done'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q20_Not sure'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q4_More than 3 times'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q4_1 time'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q12_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q20_Not sure'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q10_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q22_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q12_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q19_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q9_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q4_More than 3 times'] <= 0.5) {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q9_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q26_Yes'] <= 0.5) {
                        if ($features['Q3_Yes'] <= 0.5) {
                            if ($features['Q23_Yes'] <= 0.5) {
                                if ($features['Q2_Every 28 days'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q4_More than 3 times'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q20_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q9_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q21_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q21_Yes'] <= 0.5) {
                                        return ['risk_level' => 'None', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q2_Irregular'] <= 0.5) {
                                    if ($features['Q26_Not tested'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 75];
                                                }
                                            } else {
                                                if ($features['Q16_1-2 times/week'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q19_No'] <= 0.5) {
                                        if ($features['Q4_1 time'] <= 0.5) {
                                            if ($features['Q14_Not done'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q5_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q9_No'] <= 0.5) {
                                if ($features['Q23_No'] <= 0.5) {
                                    if ($features['Q7_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q13_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 62];
                                                }
                                            } else {
                                                if ($features['Q10_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q12_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 77];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q19_Yes'] <= 0.5) {
                                        if ($features['Q24_Yes'] <= 0.5) {
                                            if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q7_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q17_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_No'] <= 0.5) {
                                            if ($features['Q12_No'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 88];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q6_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q17_Yes'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q19_No'] <= 0.5) {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                if ($features['Q20_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q4_1 time'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q5_Yes'] <= 0.5) {
                                        if ($features['Q24_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q4_2–3 times'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                if ($features['Q11_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 91];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                if ($features['Q22_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q9_Yes'] <= 0.5) {
                            if ($features['Q5_Yes'] <= 0.5) {
                                if ($features['Q11_Yes'] <= 0.5) {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q3_No'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q3_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q23_Yes'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q13_No'] <= 0.5) {
                                            if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q3_Yes'] <= 0.5) {
                                            if ($features['Q21_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q7_Yes'] <= 0.5) {
                                        if ($features['Q12_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q20_Not sure'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q10_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Age'] <= 0.5) {
                                if ($features['Q11_Not tested'] <= 0.5) {
                                    if ($features['Q23_No'] <= 0.5) {
                                        if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                            if ($features['Q5_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q2_Irregular'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 77];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            if ($features['Q12_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        if ($features['Q22_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q12_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q13_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q27_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q4_1 time'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q18_More than 8 hours'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if ($features['Q9_No'] <= 0.5) {
                if ($features['Q26_Yes'] <= 0.5) {
                    if ($features['Q1_No'] <= 0.5) {
                        if ($features['Q25_Not Applicable'] <= 0.5) {
                            if ($features['Q16_Rarely'] <= 0.5) {
                                if ($features['Q11_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q12_Not tested'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q22_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q2_Irregular'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q22_Not tested'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q23_No'] <= 0.5) {
                                if ($features['Q5_No'] <= 0.5) {
                                    if ($features['Q27_Not sure / Don’t kNow'] <= 0.5) {
                                        if ($features['Q26_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q12_Not tested'] <= 0.5) {
                                        if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                            if ($features['Q13_Yes'] <= 0.5) {
                                                if ($features['Q12_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q20_Not sure'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q6_No'] <= 0.5) {
                                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                        if ($features['Q13_No'] <= 0.5) {
                                            if ($features['Q27_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q17_No'] <= 0.5) {
                                                if ($features['Q3_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                            if ($features['Q11_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q22_No'] <= 0.5) {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q11_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q20_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q25_Yes'] <= 0.5) {
                            if ($features['Q3_Yes'] <= 0.5) {
                                if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                    if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                        if ($features['Q12_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q13_Yes'] <= 0.5) {
                                    if ($features['Q12_No'] <= 0.5) {
                                        if ($features['Q8_No'] <= 0.5) {
                                            if ($features['Q23_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q26_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q23_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q11_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            }
                        } else {
                            if ($features['Q27_Not sure / Don’t kNow'] <= 0.5) {
                                if ($features['Age'] <= 0.5) {
                                    return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        if ($features['Q4_1 time'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q3_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 87];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q22_Not tested'] <= 0.5) {
                                            if ($features['Q4_1 time'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q26_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q4_2–3 times'] <= 0.5) {
                                                if ($features['Q11_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q22_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q17_No'] <= 0.5) {
                                        if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                            return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q22_Yes'] <= 0.5) {
                        if ($features['Q12_Yes'] <= 0.5) {
                            if ($features['Q6_Yes'] <= 0.5) {
                                if ($features['Q18_6-8 hours'] <= 0.5) {
                                    if ($features['Q4_1 time'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q22_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q17_No'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            }
                        } else {
                            if ($features['Q24_Yes'] <= 0.5) {
                                if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                    if ($features['Q13_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q20_Not sure'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q27_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q17_Yes'] <= 0.5) {
                                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q1_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                            if ($features['Q5_No'] <= 0.5) {
                                                return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q23_Yes'] <= 0.5) {
                            if ($features['Q25_Yes'] <= 0.5) {
                                if ($features['Q6_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q7_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Age'] <= 0.5) {
                                if ($features['Q5_Yes'] <= 0.5) {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        if ($features['Q1_No'] <= 0.5) {
                                            if ($features['Q13_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q17_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q12_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q11_Yes'] <= 0.5) {
                                        if ($features['Q18_6-8 hours'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            if ($features['Q24_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q12_Not tested'] <= 0.5) {
                                                if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'High Risk', 'confidence_score' => 91];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                    if ($features['Q7_Yes'] <= 0.5) {
                                        if ($features['Q21_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q20_Not sure'] <= 0.5) {
                                                return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q16_1-2 times/week'] <= 0.5) {
                                            if ($features['Q21_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q11_No'] <= 0.5) {
                                                    return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'High Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($features['Q25_Not Applicable'] <= 0.5) {
                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                        if ($features['Q23_Yes'] <= 0.5) {
                            if ($features['Q1_No'] <= 0.5) {
                                if ($features['Q8_No'] <= 0.5) {
                                    if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q26_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q13_No'] <= 0.5) {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q13_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q19_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q11_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q20_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q16_1-2 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Age'] <= 0.5) {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            } else {
                                if ($features['Q20_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q20_No'] <= 0.5) {
                            if ($features['Q3_No'] <= 0.5) {
                                if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q12_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q8_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q22_Not tested'] <= 0.5) {
                                    if ($features['Q7_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                if ($features['Q1_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 80];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q5_No'] <= 0.5) {
                                if ($features['Q19_No'] <= 0.5) {
                                    if ($features['Q11_Not tested'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q12_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q17_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q7_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q23_No'] <= 0.5) {
                        if ($features['Q12_Yes'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q1_Yes'] <= 0.5) {
                                    if ($features['Q16_1-2 times/week'] <= 0.5) {
                                        if ($features['Q11_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                                if ($features['Q22_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q8_No'] <= 0.5) {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                if ($features['Q26_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q12_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q3_No'] <= 0.5) {
                                        if ($features['Q6_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q21_No'] <= 0.5) {
                                            if ($features['Q17_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q1_Yes'] <= 0.5) {
                                    if ($features['Q16_1-2 times/week'] <= 0.5) {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q7_Yes'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q5_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q22_Yes'] <= 0.5) {
                                if ($features['Q5_No'] <= 0.5) {
                                    if ($features['Q4_1 time'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q16_1-2 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q11_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q10_No'] <= 0.5) {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q3_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q8_No'] <= 0.5) {
                                            if ($features['Q20_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            }
                        }
                    } else {
                        if ($features['Q7_No'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q8_Yes'] <= 0.5) {
                                    if ($features['Q4_More than 3 times'] <= 0.5) {
                                        if ($features['Q26_Yes'] <= 0.5) {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q6_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q11_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q4_2–3 times'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q19_Yes'] <= 0.5) {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q5_No'] <= 0.5) {
                                        if ($features['Q12_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q12_Yes'] <= 0.5) {
                                if ($features['Q22_Yes'] <= 0.5) {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q26_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                        if ($features['Q4_More than 3 times'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q5_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q3_Yes'] <= 0.5) {
                                    if ($features['Q26_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q13_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q22_Yes'] <= 0.5) {
                                            if ($features['Q11_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q13_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                        if ($features['Q10_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q11_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        if ($features['Q11_No'] <= 0.5) {
            if ($features['Q25_Yes'] <= 0.5) {
                if ($features['Q1_Yes'] <= 0.5) {
                    if ($features['Q23_Yes'] <= 0.5) {
                        if ($features['Q8_No'] <= 0.5) {
                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                if ($features['Q5_No'] <= 0.5) {
                                    if ($features['Q26_Yes'] <= 0.5) {
                                        if ($features['Q20_No'] <= 0.5) {
                                            if ($features['Q14_No'] <= 0.5) {
                                                if ($features['Q10_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q3_No'] <= 0.5) {
                                        if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q3_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q11_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q16_1-2 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q9_No'] <= 0.5) {
                                if ($features['Q14_Yes'] <= 0.5) {
                                    if ($features['Q11_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q12_Not tested'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                            }
                        }
                    } else {
                        if ($features['Q26_Yes'] <= 0.5) {
                            if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                if ($features['Q10_Yes'] <= 0.5) {
                                    if ($features['Q14_Yes'] <= 0.5) {
                                        if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q26_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q18_6-8 hours'] <= 0.5) {
                                            if ($features['Q20_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q6_No'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q21_Yes'] <= 0.5) {
                                                if ($features['Q9_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 71];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q14_Yes'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q12_Not tested'] <= 0.5) {
                                            if ($features['Q9_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q26_Not tested'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q12_Yes'] <= 0.5) {
                                if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q3_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q11_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            } else {
                                if ($features['Age'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q3_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q14_Yes'] <= 0.5) {
                        if ($features['Age'] <= 0.5) {
                            if ($features['Q13_No'] <= 0.5) {
                                if ($features['Q10_Yes'] <= 0.5) {
                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                } else {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                }
                            } else {
                                if ($features['Q7_Yes'] <= 0.5) {
                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q9_Yes'] <= 0.5) {
                                        return ['risk_level' => 'None', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q5_Yes'] <= 0.5) {
                                if ($features['Q24_Not Applicable'] <= 0.5) {
                                    if ($features['Q18_More than 8 hours'] <= 0.5) {
                                        if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q14_Not done'] <= 0.5) {
                                                if ($features['Q26_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q11_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q3_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q10_Yes'] <= 0.5) {
                                                if ($features['Q9_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q9_Yes'] <= 0.5) {
                                        return ['risk_level' => 'None', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q2_Irregular'] <= 0.5) {
                                    if ($features['Q12_No'] <= 0.5) {
                                        if ($features['Q18_More than 8 hours'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q2_More than 2 months gap'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q8_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                if ($features['Q10_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q18_More than 8 hours'] <= 0.5) {
                                        if ($features['Q9_Yes'] <= 0.5) {
                                            if ($features['Q12_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q11_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q6_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q17_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q26_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q20_No'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q21_Yes'] <= 0.5) {
                            if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                if ($features['Q12_Not tested'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q18_6-8 hours'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q5_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q11_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q12_Yes'] <= 0.5) {
                                if ($features['Q5_No'] <= 0.5) {
                                    if ($features['Q8_Yes'] <= 0.5) {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                }
                            } else {
                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q26_Yes'] <= 0.5) {
                                        if ($features['Q18_More than 8 hours'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($features['Q3_Yes'] <= 0.5) {
                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                        if ($features['Q7_No'] <= 0.5) {
                            if ($features['Q1_No'] <= 0.5) {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        if ($features['Q10_No'] <= 0.5) {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q11_Not tested'] <= 0.5) {
                                                if ($features['Q21_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q14_Not done'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q20_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q17_No'] <= 0.5) {
                                if ($features['Q14_Not done'] <= 0.5) {
                                    if ($features['Q13_Yes'] <= 0.5) {
                                        if ($features['Q23_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q6_No'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q5_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Age'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q11_Yes'] <= 0.5) {
                                        if ($features['Q10_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q9_Yes'] <= 0.5) {
                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                            } else {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Q17_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            }
                        } else {
                            if ($features['Q14_No'] <= 0.5) {
                                if ($features['Q1_Yes'] <= 0.5) {
                                    if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q20_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q7_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q23_No'] <= 0.5) {
                        if ($features['Q11_Yes'] <= 0.5) {
                            if ($features['Q2_Irregular'] <= 0.5) {
                                if ($features['Q22_Yes'] <= 0.5) {
                                    if ($features['Q21_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q18_6-8 hours'] <= 0.5) {
                                            if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q14_Not done'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            }
                        } else {
                            if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            } else {
                                if ($features['Q17_Yes'] <= 0.5) {
                                    if ($features['Q19_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            }
                        }
                    } else {
                        if ($features['Age'] <= 0.5) {
                            if ($features['Q14_Yes'] <= 0.5) {
                                if ($features['Q9_Yes'] <= 0.5) {
                                    if ($features['Q21_No'] <= 0.5) {
                                        if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                            if ($features['Q11_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 75];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q13_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q12_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q7_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q1_No'] <= 0.5) {
                                        if ($features['Q21_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                        }
                    }
                }
            }
        } else {
            if ($features['Q26_Yes'] <= 0.5) {
                if ($features['Q23_No'] <= 0.5) {
                    if ($features['Q5_Yes'] <= 0.5) {
                        if ($features['Q25_Yes'] <= 0.5) {
                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                if ($features['Q14_No'] <= 0.5) {
                                    if ($features['Q10_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q2_Irregular'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q10_Yes'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 71];
                                                }
                                            } else {
                                                if ($features['Q16_Rarely'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 62];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                }
                                            }
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 91];
                                                }
                                            } else {
                                                if ($features['Q24_No'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Q20_No'] <= 0.5) {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 53];
                                                }
                                            } else {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 57];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 93];
                                                }
                                            }
                                        } else {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 75];
                                                }
                                            } else {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 77];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 90];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q10_No'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q14_Not done'] <= 0.5) {
                                            if ($features['Q17_Yes'] <= 0.5) {
                                                if ($features['Q2_Every 28 days'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 76];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 84];
                                                }
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 75];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 92];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            if ($features['Q27_Not sure / Don’t kNow'] <= 0.5) {
                                                if ($features['Q27_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 73];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 75];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q9_Yes'] <= 0.5) {
                                                if ($features['Q12_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 95];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 66];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q12_No'] <= 0.5) {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                if ($features['Q27_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            } else {
                                                if ($features['Q20_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q14_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q26_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q13_No'] <= 0.5) {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Q1_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                }
                            } else {
                                if ($features['Q16_Rarely'] <= 0.5) {
                                    if ($features['Q3_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q20_No'] <= 0.5) {
                                        if ($features['Q27_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q17_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q22_Yes'] <= 0.5) {
                            if ($features['Q17_Yes'] <= 0.5) {
                                if ($features['Q25_Yes'] <= 0.5) {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Q14_No'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 50];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                                if ($features['Q3_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 84];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q10_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 76];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_Yes'] <= 0.5) {
                                            if ($features['Q27_No'] <= 0.5) {
                                                if ($features['Q2_Every 28 days'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 81];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 70];
                                                }
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q27_Not sure / Don’t kNow'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q21_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 66];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q8_Yes'] <= 0.5) {
                                        if ($features['Q1_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q2_Irregular'] <= 0.5) {
                                    if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                        if ($features['Q12_Yes'] <= 0.5) {
                                            if ($features['Q21_No'] <= 0.5) {
                                                if ($features['Q27_Not Applicable'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 66];
                                                }
                                            } else {
                                                if ($features['Q13_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 64];
                                                }
                                            }
                                        } else {
                                            if ($features['Q20_No'] <= 0.5) {
                                                if ($features['Q10_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 84];
                                                }
                                            } else {
                                                if ($features['Q16_Rarely'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q3_Yes'] <= 0.5) {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            } else {
                                                if ($features['Q20_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q1_No'] <= 0.5) {
                                if ($features['Q21_Yes'] <= 0.5) {
                                    if ($features['Q20_Yes'] <= 0.5) {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                if ($features['Q27_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 63];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q24_Yes'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 75];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q12_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 87];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q17_Yes'] <= 0.5) {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q14_Not done'] <= 0.5) {
                                    if ($features['Q12_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q15_Household work- cleaning, mopping, cooking, daily chore'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q14_No'] <= 0.5) {
                        if ($features['Q25_Not Applicable'] <= 0.5) {
                            if ($features['Q13_No'] <= 0.5) {
                                if ($features['Q14_Yes'] <= 0.5) {
                                    if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q7_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q16_1-2 times/week'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q7_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                    if ($features['Q22_No'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'None', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q5_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Age'] <= 0.5) {
                                return ['risk_level' => 'None', 'confidence_score' => 100];
                            } else {
                                if ($features['Q6_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q24_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q25_Yes'] <= 0.5) {
                            if ($features['Q12_Yes'] <= 0.5) {
                                if ($features['Q3_Yes'] <= 0.5) {
                                    if ($features['Q6_Yes'] <= 0.5) {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Q7_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 98];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q16_Rarely'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q5_Yes'] <= 0.5) {
                                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 98];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                }
                                            } else {
                                                if ($features['Q10_No'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 85];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q27_Not Applicable'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q18_More than 8 hours'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q2_Irregular'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q20_Yes'] <= 0.5) {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                            if ($features['Q6_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 90];
                                                }
                                            }
                                        } else {
                                            if ($features['Q17_No'] <= 0.5) {
                                                if ($features['Q5_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 68];
                                                }
                                            } else {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 91];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q10_No'] <= 0.5) {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 76];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 72];
                                                }
                                            } else {
                                                if ($features['Q19_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 75];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 85];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                }
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q17_No'] <= 0.5) {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q22_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                if ($features['Q22_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 66];
                                                }
                                            } else {
                                                if ($features['Q5_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            if ($features['Q6_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 90];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            }
                                        } else {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($features['Q27_No'] <= 0.5) {
                                if ($features['Q7_No'] <= 0.5) {
                                    if ($features['Age'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q12_Not tested'] <= 0.5) {
                                        if ($features['Q9_No'] <= 0.5) {
                                            if ($features['Q10_Yes'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q26_Not tested'] <= 0.5) {
                                                if ($features['Q16_Rarely'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 90];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            if ($features['Q3_Yes'] <= 0.5) {
                                                if ($features['Q5_No'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q18_More than 8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q3_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q16_more than 3 times/week'] <= 0.5) {
                                    if ($features['Q20_Yes'] <= 0.5) {
                                        if ($features['Q22_Yes'] <= 0.5) {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q21_Yes'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q17_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q10_Yes'] <= 0.5) {
                                        if ($features['Q21_No'] <= 0.5) {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($features['Q23_Yes'] <= 0.5) {
                    if ($features['Q10_No'] <= 0.5) {
                        if ($features['Q12_Yes'] <= 0.5) {
                            if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                if ($features['Q5_No'] <= 0.5) {
                                    if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Q17_Yes'] <= 0.5) {
                                                if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 85];
                                                }
                                            } else {
                                                if ($features['Q27_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            }
                                        } else {
                                            if ($features['Q14_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 90];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q13_Yes'] <= 0.5) {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q22_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                } else {
                                    if ($features['Q24_Yes'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            if ($features['Q20_No'] <= 0.5) {
                                                if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 54];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q7_Yes'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q25_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q13_Yes'] <= 0.5) {
                                    if ($features['Q20_Yes'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            if ($features['Q5_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q20_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 60];
                                                }
                                            }
                                        } else {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            }
                        } else {
                            if ($features['Q1_Yes'] <= 0.5) {
                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                            } else {
                                if ($features['Age'] <= 0.5) {
                                    if ($features['Q5_Yes'] <= 0.5) {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q3_Yes'] <= 0.5) {
                                        if ($features['Q22_Not tested'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q20_No'] <= 0.5) {
                                                if ($features['Q27_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 80];
                                                }
                                            } else {
                                                if ($features['Q2_Every 28 days'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 75];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q19_No'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($features['Q17_Yes'] <= 0.5) {
                            if ($features['Q5_Yes'] <= 0.5) {
                                if ($features['Q1_Yes'] <= 0.5) {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Q12_Yes'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            if ($features['Q20_Not sure'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    } else {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                if ($features['Q22_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 80];
                                                }
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        } else {
                                            return ['risk_level' => 'None', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                    if ($features['Q14_No'] <= 0.5) {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q12_Yes'] <= 0.5) {
                                            if ($features['Q25_Yes'] <= 0.5) {
                                                if ($features['Age'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 93];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q27_No'] <= 0.5) {
                                                if ($features['Q22_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q14_Yes'] <= 0.5) {
                                        if ($features['Q20_No'] <= 0.5) {
                                            if ($features['Q2_Every 28 days'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q21_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        } else {
                            if ($features['Q3_Yes'] <= 0.5) {
                                if ($features['Q12_Yes'] <= 0.5) {
                                    if ($features['Q21_Yes'] <= 0.5) {
                                        if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                            if ($features['Q25_Yes'] <= 0.5) {
                                                if ($features['Q2_Irregular'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 92];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q20_Not sure'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q22_Not tested'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q15_Low physical activity / mostly inactive'] <= 0.5) {
                                            if ($features['Q22_Yes'] <= 0.5) {
                                                if ($features['Q24_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 78];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 83];
                                                }
                                            } else {
                                                if ($features['Q27_Not Applicable'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 92];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Q18_6-8 hours'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q18_6-8 hours'] <= 0.5) {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q22_No'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        }
                                    } else {
                                        if ($features['Q20_No'] <= 0.5) {
                                            if ($features['Q24_Not Applicable'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q24_Yes'] <= 0.5) {
                                                return ['risk_level' => 'None', 'confidence_score' => 100];
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q20_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q13_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($features['Q9_No'] <= 0.5) {
                        if ($features['Q18_More than 8 hours'] <= 0.5) {
                            if ($features['Q20_Yes'] <= 0.5) {
                                if ($features['Q19_No'] <= 0.5) {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q7_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                }
                            } else {
                                if ($features['Q25_Yes'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                }
                            }
                        } else {
                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                        }
                    } else {
                        if ($features['Q3_No'] <= 0.5) {
                            if ($features['Q15_Both exercise and household work'] <= 0.5) {
                                if ($features['Age'] <= 0.5) {
                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                } else {
                                    if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        if ($features['Q20_Yes'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            } else {
                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                            }
                        } else {
                            if ($features['Q5_No'] <= 0.5) {
                                if ($features['Q6_No'] <= 0.5) {
                                    if ($features['Q21_No'] <= 0.5) {
                                        return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                    } else {
                                        return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                    }
                                } else {
                                    if ($features['Age'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q19_No'] <= 0.5) {
                                                if ($features['Q22_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            } else {
                                                if ($features['Q10_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q16_Rarely'] <= 0.5) {
                                            if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            } else {
                                                if ($features['Q17_Yes'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 92];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                }
                                            }
                                        } else {
                                            if ($features['Age'] <= 0.5) {
                                                if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 50];
                                                }
                                            } else {
                                                if ($features['Q18_6-8 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($features['Q12_Yes'] <= 0.5) {
                                    if ($features['Q22_Yes'] <= 0.5) {
                                        if ($features['Q17_No'] <= 0.5) {
                                            if ($features['Q8_Yes'] <= 0.5) {
                                                if ($features['Q19_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 93];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 66];
                                                }
                                            } else {
                                                return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                            }
                                        } else {
                                            if ($features['Q16_Rarely'] <= 0.5) {
                                                if ($features['Q18_Less than 6 hours'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 51];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 90];
                                                }
                                            } else {
                                                if ($features['Q2_Every 28 days'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 55];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Q2_Every 21-35 days'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q21_Yes'] <= 0.5) {
                                                if ($features['Q17_No'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 85];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 75];
                                                }
                                            } else {
                                                return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                            }
                                        }
                                    }
                                } else {
                                    if ($features['Q6_Yes'] <= 0.5) {
                                        if ($features['Q10_No'] <= 0.5) {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        } else {
                                            if ($features['Q22_Not tested'] <= 0.5) {
                                                if ($features['Q16_Rarely'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                                } else {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 87];
                                                }
                                            } else {
                                                if ($features['Q15_Regular exercise - gym, yoga, walking, sports'] <= 0.5) {
                                                    return ['risk_level' => 'Low Risk', 'confidence_score' => 90];
                                                } else {
                                                    return ['risk_level' => 'None', 'confidence_score' => 100];
                                                }
                                            }
                                        }
                                    } else {
                                        if ($features['Age'] <= 0.5) {
                                            return ['risk_level' => 'Moderate Risk', 'confidence_score' => 100];
                                        } else {
                                            return ['risk_level' => 'Low Risk', 'confidence_score' => 100];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
